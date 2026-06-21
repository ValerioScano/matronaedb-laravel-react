<?php

namespace App\Http\Controllers;

use App\Enums\Macroarea;
use App\Enums\Province;
use App\Mail\ApprovedProposal;
use App\Mail\RejectedProposal;
use App\Models\Edition;
use App\Models\ExternalResource;
use App\Models\Filing;
use App\Models\Proposal;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ProposalController extends Controller
{
    /**
     * Resolve the edition_image value for an edition payload: stores a newly
     * uploaded file, clears it when the removal flag is set, or otherwise
     * keeps whatever the existing edition already had.
     */
    private function resolveEditionImage(array $edition, ?Edition $existingEdition = null): ?string
    {
        if (array_key_exists('edition_image', $edition)) {
            if ($existingEdition?->edition_image) {
                Storage::delete($existingEdition->edition_image);
            }

            return Storage::putFile('editions', $edition['edition_image']);
        }

        if (! empty($edition['remove_edition_image'])) {
            if ($existingEdition?->edition_image) {
                Storage::delete($existingEdition->edition_image);
            }

            return null;
        }

        return $existingEdition?->edition_image;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_proposals = Proposal::withTrashed()->where('proposed_by', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);

        return view('pages.proposals.index', compact('user_proposals'));
    }

    public function show(int $id)
    {
        $proposal = Proposal::withTrashed()->findOrFail($id);
        $proposal->load(['editions' => function ($query) {
            $query->withTrashed();
        }, 'people', 'externalResources']);
        $groupedTags = $proposal->tags->groupBy('category');
        $filing = null;
        $groupedTagsFiling = null;

        /** @var User $user */
        $user = Auth::user();

        if ($proposal->filing_id && $proposal->status === 'pending' && $user?->isAdmin()) {
            $filing = Filing::withTrashed()->with(['editions', 'tags', 'people', 'externalResources'])->find($proposal->filing_id);
            $groupedTagsFiling = $filing->tags->groupBy('category');
        }

        $previousId = Proposal::when(! $user?->isAdmin(), function ($query) {
            $query->where('proposed_by', Auth::id());
        })
            ->where('id', '<', $proposal->id)
            ->orderBy('id', 'desc')
            ->value('id');

        $nextId = Proposal::when(! $user?->isAdmin(), function ($query) {
            $query->where('proposed_by', Auth::id());
        })
            ->where('id', '>', $proposal->id)
            ->orderBy('id', 'asc')
            ->value('id');

        return view('pages.proposals.show', compact('proposal', 'groupedTags', 'previousId', 'nextId', 'filing', 'groupedTagsFiling'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pairedTags = $this->buildTagPairs(Tag::all());

        return view('pages.proposals.create', compact('pairedTags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'macroarea' => ['required', Rule::in(Macroarea::values())],
            'province' => ['required', Rule::in(Province::values())],
            'editions.*.edition_type' => 'required_with:editions',

            'religion' => 'required|in:uncertain,pagan,christian',
            'editions.*.corpus' => 'required_with:editions',
        ], [], [
            'editions.*.edition_type' => 'edition type',
            'editions.*.corpus' => 'corpus',
        ]);
        $data = $request->all();

        $proposal = new Proposal;
        $proposal->text = $data['text'];
        $proposal->macroarea = $data['macroarea'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->private_notes = $data['private_notes'] ?? null;
        $proposal->status = 'pending';
        $proposal->proposed_by = Auth::id();
        $proposal->save();

        // 2. Salva le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {
                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'edition_type' => $edition['edition_type'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                    'publication_place' => $edition['publication_place'] ?? null,
                    'link' => $edition['link'] ?? null,
                ];

                $editionData['edition_image'] = $this->resolveEditionImage($edition);

                $proposal->editions()->create($editionData);
            }
        }

        // 3. Salva le persone
        if (array_key_exists('people', $data)) {
            foreach ($data['people'] as $personData) {
                $proposal->people()->create([
                    'praenomen'  => $personData['praenomen'] ?? null,
                    'nomen'      => $personData['nomen'] ?? null,
                    'cognomen'   => $personData['cognomen'] ?? null,
                    'TM_PER_id'  => $personData['TM_PER_id'] ?? null,
                ]);
            }
        }

        // 4. Salva le risorse esterne
        if (array_key_exists('external_resources', $data)) {
            foreach ($data['external_resources'] as $resourceData) {
                $proposal->externalResources()->create([
                    'name' => $resourceData['name'] ?? null,
                    'link' => $resourceData['link'] ?? null,
                ]);
            }
        }

        // 5. Salva i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        }

        return redirect()->route('proposals.show', $proposal);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        $proposal->load(['editions', 'people', 'externalResources']);
        $pairedTags = $this->buildTagPairs(Tag::all());

        return view('pages.proposals.edit', compact('proposal', 'pairedTags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'text' => 'required',
            'macroarea' => ['required', Rule::in(Macroarea::values())],
            'province' => ['required', Rule::in(Province::values())],
            'editions.*.edition_type' => 'required_with:editions',

            'religion' => 'required|in:uncertain,pagan,christian',
            'editions.*.corpus' => 'required_with:editions',
        ], [], [
            'editions.*.edition_type' => 'edition type',
            'editions.*.corpus' => 'corpus',
        ]);
        $data = $request->all();

        // 1. Aggiorna la proposal
        $proposal->text = $data['text'];
        $proposal->macroarea = $data['macroarea'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->private_notes = $data['private_notes'] ?? null;
        $proposal->status = 'pending';
        $proposal->save();

        // 2. Aggiorna le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {

                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'edition_type' => $edition['edition_type'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                    'publication_place' => $edition['publication_place'] ?? null,
                    'link' => $edition['link'] ?? null,
                ];

                $existingEdition = isset($edition['id']) ? Edition::find($edition['id']) : null;
                $editionData['edition_image'] = $this->resolveEditionImage($edition, $existingEdition);

                if ($existingEdition) {
                    // Aggiorna edizione esistente
                    $existingEdition->update($editionData);
                } else {
                    // Crea nuova edizione collegata alla proposal
                    $proposal->editions()->create($editionData);
                }
            }
        }
        // 3. Aggiorna le persone
        $proposal->people()->delete();
        if (array_key_exists('people', $data)) {
            foreach ($data['people'] as $personData) {
                $proposal->people()->create([
                    'praenomen'  => $personData['praenomen'] ?? null,
                    'nomen'      => $personData['nomen'] ?? null,
                    'cognomen'   => $personData['cognomen'] ?? null,
                    'TM_PER_id'  => $personData['TM_PER_id'] ?? null,
                ]);
            }
        }

        // 4. Aggiorna le risorse esterne
        $proposal->externalResources()->delete();
        if (array_key_exists('external_resources', $data)) {
            foreach ($data['external_resources'] as $resourceData) {
                $proposal->externalResources()->create([
                    'name' => $resourceData['name'] ?? null,
                    'link' => $resourceData['link'] ?? null,
                ]);
            }
        }

        // 5. Aggiorna i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        } else {
            $proposal->tags()->detach();
        }

        /** @var User $user */
        $user = Auth::user();
        if ($request->approve_after_edit && $user->isAdmin()) {
            return $this->approve($proposal, $request);
        }

        return redirect()->route('proposals.show', $proposal);
    }

    public function destroy(Proposal $proposal)
    {
        foreach ($proposal->editions as $edition) {
            if ($edition->edition_image) {
                Storage::delete($edition->edition_image);
            }
        }

        $proposal->editions()->delete();
        $proposal->people()->delete();
        $proposal->externalResources()->delete();
        $proposal->tags()->detach();
        $proposal->delete();

        return redirect()->route('proposals.index');
    }

    public function createRevision(Filing $filing)
    {
        $filing->load(['editions', 'people', 'externalResources']);
        $pairedTags = $this->buildTagPairs(Tag::all());

        return view('pages.proposals.create_revision', compact('filing', 'pairedTags'));
    }

    public function storeRevision(Request $request, Filing $filing)
    {
        $request->validate([
            'text' => 'required',
            'macroarea' => ['required', Rule::in(Macroarea::values())],
            'province' => ['required', Rule::in(Province::values())],
            'editions.*.edition_type' => 'required_with:editions',

            'religion' => 'required|in:uncertain,pagan,christian',
            'editions.*.corpus' => 'required_with:editions',
        ], [], [
            'editions.*.edition_type' => 'edition type',
            'editions.*.corpus' => 'corpus',
        ]);
        $data = $request->all();

        // 1. Crea la nuova proposal con filing_id valorizzato
        $proposal = new Proposal;
        $proposal->filing_id = $filing->id;
        $proposal->proposed_by = Auth::id();
        $proposal->text = $data['text'];
        $proposal->macroarea = $data['macroarea'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->private_notes = $data['private_notes'] ?? null;
        $proposal->status = 'pending';
        $proposal->save();

        // 2. Salva le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {
                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'edition_type' => $edition['edition_type'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                    'publication_place' => $edition['publication_place'] ?? null,
                    'link' => $edition['link'] ?? null,
                ];

                $editionData['edition_image'] = $this->resolveEditionImage($edition);

                $proposal->editions()->create($editionData);
            }
        }

        // 3. Salva le persone
        if (array_key_exists('people', $data)) {
            foreach ($data['people'] as $personData) {
                $proposal->people()->create([
                    'praenomen'  => $personData['praenomen'] ?? null,
                    'nomen'      => $personData['nomen'] ?? null,
                    'cognomen'   => $personData['cognomen'] ?? null,
                    'TM_PER_id'  => $personData['TM_PER_id'] ?? null,
                ]);
            }
        }

        // 4. Salva le risorse esterne
        if (array_key_exists('external_resources', $data)) {
            foreach ($data['external_resources'] as $resourceData) {
                $proposal->externalResources()->create([
                    'name' => $resourceData['name'] ?? null,
                    'link' => $resourceData['link'] ?? null,
                ]);
            }
        }

        // 5. Salva i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        }

        return redirect()->route('proposals.show', $proposal);
    }

    /**
     * Display list of all proposals.
     */
    public function pending()
    {
        $user_proposals = Proposal::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(20);

        return view('pages.proposals.pending', compact('user_proposals'));
    }

    /**
     * Approve the filing.
     */
    public function approve(Proposal $proposal, Request $request)
    {
        if ($proposal->status !== 'pending') {
            return redirect()->route('proposals.pending')
                ->with('error', 'This proposal has already been processed.');
        }
        // 1. Crea o aggiorna il filing
        if ($proposal->filing_id) {
            // È una revisione — aggiorna il filing esistente
            $filing = Filing::find($proposal->filing_id);
        } else {
            // È una nuova schedatura — crea un nuovo filing
            $filing = new Filing;
        }

        // 2. Copia i campi dalla proposal al filing
        $filing->text = $proposal->text;
        $filing->macroarea = $proposal->macroarea;
        $filing->province = $proposal->province;
        $filing->city = $proposal->city;
        $filing->min_year = $proposal->min_year;
        $filing->max_year = $proposal->max_year;
        $filing->is_certain_date = $proposal->is_certain_date;
        $filing->is_sacred_dedication = $proposal->is_sacred_dedication;
        $filing->religion = $proposal->religion;
        $filing->notes = $proposal->notes;
        $filing->proposed_by = $proposal->proposed_by;
        $filing->approved_by = Auth::id();
        $filing->save();

        // 3. Copia le edizioni
        $filing->editions()->delete(); // elimina le vecchie se è una revisione
        foreach ($proposal->editions as $edition) {
            $filing->editions()->create([
                'corpus' => $edition->corpus,
                'volume' => $edition->volume,
                'number_inscription' => $edition->number_inscription,
                'edition_type' => $edition->edition_type,
                'publication_year' => $edition->publication_year,
                'corpus_page' => $edition->corpus_page,
                'last_name_author' => $edition->last_name_author,
                'publication_place' => $edition->publication_place,
                'edition_image' => $edition->edition_image,
                'link' => $edition['link'] ?? null,
            ]);
        }

        // 4. Copia le persone
        $filing->people()->delete();
        foreach ($proposal->people as $person) {
            $filing->people()->create([
                'praenomen' => $person->praenomen,
                'nomen'     => $person->nomen,
                'cognomen'  => $person->cognomen,
                'TM_PER_id' => $person->TM_PER_id,
            ]);
        }

        // 4b. Copia le risorse esterne
        $filing->externalResources()->delete();
        foreach ($proposal->externalResources as $resource) {
            $filing->externalResources()->create([
                'name' => $resource->name,
                'link' => $resource->link,
            ]);
        }

        // 5. Copia i tags
        $filing->tags()->sync($proposal->tags->pluck('id'));

        // 6. Aggiorna lo stato della proposal
        $proposal->status = 'approved';
        $proposal->approved_by = Auth::id();
        $proposal->filing_id = $filing->id;
        $proposal->save();

        Mail::to($proposal->proposedBy)->send(new ApprovedProposal($proposal));
        return redirect()->route('proposals.pending');
    }

    /**
     * Reject the filing.
     */
    public function reject(Proposal $proposal, Request $request)
    {
        $proposal->status = 'rejected';
        $proposal->rejection_notes = $request->rejection_notes;
        $proposal->approved_by = Auth::id();
        $proposal->save();

        Mail::to($proposal->proposedBy)->send(new RejectedProposal($proposal));
        return redirect()->route('proposals.pending');
    }
}
