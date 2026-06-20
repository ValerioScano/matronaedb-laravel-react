@extends('layouts.operational')


@section('content')
    <div class="row text-center flex-column py-5">
        <div class="col-12">
            <h1>Revision on MATR {{ $filing->id }}</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proposals.revisions.store', $filing) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row align-items-center gx-5 p-4 border border-primary-subtle border-opacity-25 rounded-4">

            {{-- Testo iscrizione --}}
            <h3>Inscription's details</h3>
            <div class="col-12 my-3">
                <label for="text" class="form-label">Add the inscription's text <span
                        class="text-danger">*</span></label> <a data-bs-toggle="offcanvas" href="#offcanvas" role="button"
                    aria-controls="offcanvas"><i class="bi bi-info-circle"></i></a>
                <textarea name="text" class="form-control @error('text') is-invalid @enderror" id="text"
                    aria-describedby="text-help" required>{{ old('text', $filing->text) }}</textarea>
            </div>
            @error('text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasLabel">Offcanvas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                    <h5>Transcription Conventions</h5>

                    <ol>
                        <li>
                            The text of the inscription must be transcribed in lowercase letters.

                        </li>
                        <li>
                            Line breaks are marked with a / where the text begins a new line. N.B. If a line break falls
                            within a
                            word, leave no spaces between the / and the letters immediately before and after it.
                        </li>
                        <li>
                            abc— legible letters that can be assigned to words.

                        </li>
                        <li>
                            ABC — legible letters that are difficult to assign to words.

                        </li>
                        <li>
                            abc (with dots below) — letters of uncertain reading.

                        </li>
                        <li>
                            +++ — traces of damaged letters.

                        </li>
                        <li>
                            abc (underlined) — letters read in antiquity but no longer legible.

                        </li>
                        <li>
                            àèìòù — vowels marked with an apex.

                        </li>
                        <li>ì — i longa (a tall I).</li>
                        <li>abc (with a line above) — overlined letters or numerals.</li>
                        <li>ab (with a circumflex over the “a”) — ligatured letters (nexus)</li>
                        <li>⟦abc⟧ (double square brackets) — erased letter.</li>
                        <li>⟪abc⟫ (double angle brackets) — letters carved in place of others that were erased.</li>
                        <li>‘abc’ — ancient additions or corrections.</li>
                        <li>[- - -] — lost letters</li>
                        <li>[abc] — lost letters that can be restored.</li>
                        <li>
                            [- - - - - -] — a gap of one entire line

                        </li>
                        <li> - - - - - - — a gap of an uncertain number of lines.</li>
                        <li><- - -> — words omitted (by the engraver) because the text is incomplete.</li>
                        <li>
                            <:abc> — implied words

                        </li>
                        <li> a(bc) — expansion of an abbreviated word.</li>
                        <li>
                            a(- - -) — an abbreviation that cannotbe expanded.

                        </li>
                        <li>((abc)) — a word inserted to replace a symbol.</li>
                        <li>((:bc)) — a word inserted in place of a figure.</p>
                        <li>
                            (:abc) — correction of an incorrect word, or expansion of numerals, of words containing
                            numbers,
                            or of abbreviations written with repeated letters to mark the plural (e.g. Augg.).
                        </li>

                    </ol>
                </div>
            </div>
        </div>
        {{-- Localizzazione iscrizione --}}

        <div class="col-12 col-md-4 mb-3">
            <label for="macroarea" class="form-label">Macroarea <span class="text-danger">*</span></label>
            <input name="macroarea" type="text" class="form-control @error('macroarea') is-invalid @enderror"
                id="macroarea" required autocomplete="off" list="macroarea-suggestions"
                value="{{ old('macroarea', $filing->macroarea) }}">
            <datalist id="macroarea-suggestions">
                @foreach (\App\Enums\Macroarea::cases() as $macroarea)
                    <option value="{{ $macroarea->value }}">
                @endforeach
            </datalist>
            @error('macroarea')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-4 mb-3">
            <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
            <input name="province" type="text" class="form-control @error('province') is-invalid @enderror"
                id="province" required list="province-suggestions" value="{{ old('province', $filing->province) }}">
            <datalist id="province-suggestions">
                @foreach (\App\Enums\Province::cases() as $province)
                    <option value="{{ $province->value }}">
                @endforeach
            </datalist>
            @error('province')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-4 mb-3">
            <label for="city" class="form-label">City</label>
            <input name="city" type="text" class="form-control" id="city"
                value="{{ old('city', $filing->city) }}">
        </div>

        {{-- datazione iscrizione --}}
        <div class="col-12 col-md-4 mb-3">
            <label for="min_year" class="form-label">Earliest datation</label>
            <input name="min_year" type="number" class="form-control" id="min_year" min="-750" max="1800"
                value="{{ old('min_year', $filing->min_year) }}">
        </div>

        <div class="col-12 col-md-4 mb-3">
            <label for="max_year" class="form-label">Latest datation</label>
            <input name="max_year" type="number" class="form-control" id="max_year" min="-750" max="1800"
                value="{{ old('max_year', $filing->max_year) }}">
        </div>

        <div class="col-12 col-md-4 mb-3 form-check">
            <input name="is_certain_date" type="checkbox" class="form-check-input" id="is_certain_date"
                @checked(old('is_certain_date', $filing->is_certain_date))>
            <label for="is_certain_date" class="form-check-label">Is the datation sure within a 50 years
                range?</label>
        </div>

        {{-- Religione --}}

        <div class="col-12 col-md-6 mb-3 form-check">
            <input name="is_sacred_dedication" type="checkbox" class="form-check-input" id="is_sacred_dedication"
                @checked(old('is_sacred_dedication', $filing->is_sacred_dedication))>
            <label for="is_sacred_dedication" class="form-check-label">Is the inscription a sacred
                dedication?</label>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label for="religion">Religion:</label>
            <select name="religion" class="form-select @error('religion') is-invalid @enderror" id="religion">
                <option value="uncertain" @selected(old('religion', $filing->religion) === 'uncertain')>Uncertain</option>
                <option value="pagan" @selected(old('religion', $filing->religion) === 'pagan')>Pagan</option>
                <option value="christian" @selected(old('religion', $filing->religion) === 'christian')>Christian</option>
            </select>
            @error('religion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Commenti --}}

        <div class="col-12 mb-3">
            <label for="notes" class="form-label">Do you want to add a public comment on this
                inscription?</label>
            <textarea name="notes" class="form-control" id="notes" aria-describedby="notes-help">{{ old('notes', $filing->notes) }}</textarea>
            <div id="notes-help" class="form-text">This comment should be helpful to the person that wants
                to
                consult your filing </div>
        </div>

        <div class="col-12 mb-3">
            <label for="private_notes" class="form-label">Is there anything that the admin should know?</label>
            <textarea name="private_notes" id="private_notes" class="form-control">{{ old('private_notes') }}</textarea>
        </div>
        </div>

        {{-- Sezione edizioni --}}

        @php
            $editionsSource = old('editions', $filing->editions->toArray());
            $editionsOld = array_values($editionsSource);
            $editionErrors = [];
            foreach (array_keys($editionsSource) as $position => $originalKey) {
                foreach (['corpus', 'edition_type'] as $field) {
                    $errorKey = "editions.$originalKey.$field";
                    if ($errors->has($errorKey)) {
                        $editionErrors[$position + 1][$field] = $errors->first($errorKey);
                    }
                }
            }
        @endphp

        <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
            <h4>Editions</h4>
            <div class="col-12 my-3">

                <div id="editions-container" data-editions='@json($editionsOld)'
                    data-edition-errors='@json($editionErrors)'>
                    {{-- Edizioni recuperate qui --}}
                </div>

                <button type="button" class="btn btn-outline-primary" id="add-edition-btn">Add edition</button>
            </div>
        </div>

        {{-- Sezione persone --}}

        <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
            <h4>People</h4>
            <div class="col-12 my-3">
                <div id="people-container" data-people='@json(old('people', $filing->people->toArray()))'>
                    {{-- Persone recuperate qui --}}
                </div>
                <button type="button" class="btn btn-outline-primary" id="add-person-btn">Add person</button>
            </div>
        </div>

        {{-- Sezione risorse esterne --}}

        <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
            <h4>External resources</h4>
            <div class="col-12 my-3">
                <div id="external-resources-container" data-external-resources='@json(old('external_resources', $filing->externalResources->toArray()))'>
                    {{-- Risorse esterne recuperate qui --}}
                </div>
                <button type="button" class="btn btn-outline-primary" id="add-external-resource-btn">Add external
                    resource</button>
            </div>
        </div>

        {{-- Sezione tags  --}}
        <div class="row gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
            <h4 class="mb-4">Tags</h4>

            @foreach ($pairedTags as $category => $pairs)
                <div class="col-12 mb-4">
                    <h6 class="fw-bold text-uppercase text-muted mb-2 border-bottom pb-2">{{ ucfirst($category) }}</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-normal ps-0" style="width:50%">Certain</th>
                                <th class="text-muted small fw-normal" style="width:50%">Uncertain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pairs as $pair)
                                <tr>
                                    <td class="ps-0 py-1">
                                        <div class="form-check mb-0">
                                            <input type="checkbox" class="form-check-input"
                                                id="tag_{{ $pair['base']->id }}" name="tags[]"
                                                value="{{ $pair['base']->id }}" @checked(in_array($pair['base']->id, old('tags', $filing->tags->pluck('id')->toArray())))>
                                            <label class="form-check-label"
                                                for="tag_{{ $pair['base']->id }}">{{ $pair['base']->label }}</label>
                                        </div>
                                    </td>
                                    <td class="py-1">
                                        @if ($pair['uncertain'])
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="tag_{{ $pair['uncertain']->id }}" name="tags[]"
                                                    value="{{ $pair['uncertain']->id }}" @checked(in_array($pair['uncertain']->id, old('tags', $filing->tags->pluck('id')->toArray())))>
                                                <label class="form-check-label"
                                                    for="tag_{{ $pair['uncertain']->id }}">{{ $pair['uncertain']->label }}</label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <div class="col-12 mb-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

@endsection
