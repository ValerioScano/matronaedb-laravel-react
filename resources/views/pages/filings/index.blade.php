@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Browse the database</h1>
                <p>Apply filters or browse through the entire database
                    @if (Auth::user()?->isAdmin())
                        with admin privileges
                    @endif
                </p>
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#filterOffcanvas">
                    <i class="bi bi-funnel"></i> Filters
                </button>
            </div>
        </div>

        <div class="offcanvas offcanvas-top" tabindex="-1" id="filterOffcanvas" style="height: 95vh">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Search & Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body overflow-y-auto">
                <form action="{{ route('filings.index') }}" method="GET" id="filter-form">

                    {{-- Ricerca testuale --}}
                    @php
                        $searchRows = array_values(request('search', []));
                        if (empty($searchRows)) {
                            $searchRows = [['term' => '', 'within' => '', 'operator' => 'AND', 'exact' => false]];
                        }
                    @endphp
                    <div class="mb-4">
                        <h5>Text search</h5>
                        <div id="search-rows-container" data-count="{{ count($searchRows) }}">
                            @foreach ($searchRows as $i => $row)
                                <div class="search-row d-flex gap-2 align-items-center mb-2">
                                    <input type="text" name="search[{{ $i }}][term]" class="form-control"
                                        placeholder="Search term..." value="{{ $row['term'] ?? '' }}">
                                    <input type="number" name="search[{{ $i }}][within]" class="form-control w-25"
                                        placeholder="Within n chars from start" value="{{ $row['within'] ?? '' }}">
                                    <select name="search[{{ $i }}][operator]" class="form-select w-25">
                                        <option value="AND" @selected(($row['operator'] ?? 'AND') === 'AND')>AND</option>
                                        <option value="OR" @selected(($row['operator'] ?? '') === 'OR')>OR</option>
                                        <option value="NOT" @selected(($row['operator'] ?? '') === 'NOT')>NOT</option>
                                    </select>
                                    <div class="form-check form-switch mb-0 text-nowrap">
                                        <input class="form-check-input" type="checkbox" name="search[{{ $i }}][exact]"
                                            id="exact_{{ $i }}" @checked(!empty($row['exact']))>
                                        <label class="form-check-label" for="exact_{{ $i }}">Exact</label>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger remove-search-row-btn">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-secondary" id="add-search-row-btn">+ Add row</button>
                    </div>


                    {{-- Id & Datazione --}}
                    <div class="row mb-4 align-items-center">
                        <div class="col-1">
                            <label class="form-label">Filing ID</label>
                            <input type="number" name="id" class="form-control" value="{{ request('id') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">From year</label>
                            <input type="number" name="min_year" class="form-control" value="{{ request('min_year') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">To year</label>
                            <input type="number" name="max_year" class="form-control" value="{{ request('max_year') }}">
                        </div>
                        <div class="col-2 form-check mt-4 ms-2">
                            <input type="checkbox" name="is_certain_date" class="form-check-input" id="is_certain_date"
                                @checked(request('is_certain_date'))>
                            <label class="form-check-label" for="is_certain_date">Certain date</label>
                        </div>
                    </div>

                    {{-- Localizzazione --}}
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="form-label">Region</label>
                            <input type="text" name="region" class="form-control" value="{{ request('region') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Province</label>
                            <input type="text" name="province" class="form-control" value="{{ request('province') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ request('city') }}">
                        </div>
                    </div>

                    {{-- Edizioni --}}
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="form-label">Corpus</label>
                            <input type="text" name="corpus" class="form-control" value="{{ request('corpus') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-control" value="{{ request('volume') }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Inscription number</label>
                            <input type="number" name="number_inscription" class="form-control"
                                value="{{ request('number_inscription') }}">
                        </div>
                    </div>

                    {{-- Religione --}}
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label">Religion</label>
                            <select name="religion" class="form-select">
                                <option value="">All</option>
                                <option value="uncertain" @selected(request('religion') === 'uncertain')>Uncertain</option>
                                <option value="pagan" @selected(request('religion') === 'pagan')>Pagan</option>
                                <option value="christian" @selected(request('religion') === 'christian')>Christian</option>
                            </select>
                        </div>
                        <div class="col-6 form-check align-items-center mt-4 ps-5">
                            <input type="checkbox" name="is_sacred_dedication" class="form-check-input"
                                id="is_sacred_dedication" @checked(request('is_sacred_dedication'))>
                            <label class="form-check-label" for="is_sacred_dedication">Sacred dedication</label>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="mb-3">
                        <h5>Tags</h5>
                        @foreach ($pairedTags as $category => $pairs)
                            <div class="my-3">
                                <h6 class="fw-bold text-uppercase text-muted border-bottom pb-1">{{ ucfirst($category) }}</h6>
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
                                                        <input type="checkbox" class="form-check-input" id="tag_{{ $pair['base']->id }}"
                                                            name="tags[]" value="{{ $pair['base']->id }}" @checked(in_array($pair['base']->id, request('tags', [])))>
                                                        <label class="form-check-label" for="tag_{{ $pair['base']->id }}">{{ $pair['base']->label }}</label>
                                                    </div>
                                                </td>
                                                <td class="py-1">
                                                    @if ($pair['uncertain'])
                                                        <div class="form-check mb-0">
                                                            <input type="checkbox" class="form-check-input" id="tag_{{ $pair['uncertain']->id }}"
                                                                name="tags[]" value="{{ $pair['uncertain']->id }}" @checked(in_array($pair['uncertain']->id, request('tags', [])))>
                                                            <label class="form-check-label" for="tag_{{ $pair['uncertain']->id }}">{{ $pair['uncertain']->label }}</label>
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

                    {{-- People --}}
                    <div class="mb-3">
                        <h5>People</h5>
                        <div id="people-container" data-people='@json(request('people', []))'>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-person-btn">+ Add person</button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('filings.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>

                </form>
            </div>
        </div>



        {{-- Active filter badges --}}
        @php
            $badges = [];

            foreach (array_values(request('search', [])) as $i => $row) {
                if (empty($row['term'])) continue;
                $label = $i > 0 ? ($row['operator'] ?? 'AND') . ' ' : '';
                $label .= '"' . $row['term'] . '"';
                if (!empty($row['exact'])) $label .= ' · exact';
                if (!empty($row['within'])) $label .= ' · within ' . $row['within'] . ' chars';
                $badges[] = ['text' => $label, 'color' => 'primary'];
            }

            $fieldBadges = [
                'id'                  => ['prefix' => 'ID: ',            'color' => 'secondary'],
                'min_year'            => ['prefix' => 'From year: ',     'color' => 'secondary'],
                'max_year'            => ['prefix' => 'To year: ',       'color' => 'secondary'],
                'region'              => ['prefix' => 'Region: ',        'color' => 'secondary'],
                'province'            => ['prefix' => 'Province: ',      'color' => 'secondary'],
                'city'                => ['prefix' => 'City: ',          'color' => 'secondary'],
                'corpus'              => ['prefix' => 'Corpus: ',        'color' => 'secondary'],
                'volume'              => ['prefix' => 'Volume: ',        'color' => 'secondary'],
                'number_inscription'  => ['prefix' => 'Inscription #',   'color' => 'secondary'],
                'religion'            => ['prefix' => 'Religion: ',      'color' => 'secondary'],
            ];
            foreach ($fieldBadges as $param => $meta) {
                if (request()->filled($param)) {
                    $badges[] = ['text' => $meta['prefix'] . request($param), 'color' => $meta['color']];
                }
            }

            if (request('is_certain_date'))    $badges[] = ['text' => 'Certain date',      'color' => 'secondary'];
            if (request('is_sacred_dedication')) $badges[] = ['text' => 'Sacred dedication', 'color' => 'secondary'];

            foreach (request('tags', []) as $tagId) {
                $tag = $tags->firstWhere('id', $tagId);
                if ($tag) $badges[] = ['text' => $tag->label, 'color' => 'primary'];
            }

            foreach (request('people', []) as $row) {
                $parts = array_filter([
                    $row['praenomen'] ?? '',
                    $row['nomen']     ?? '',
                    $row['cognomen']  ?? '',
                    isset($row['TM_PER_id']) && $row['TM_PER_id'] !== '' ? 'TM '.$row['TM_PER_id'] : '',
                ]);
                if (!empty($parts)) {
                    $badges[] = ['text' => implode(' ', $parts), 'color' => 'info'];
                }
            }
        @endphp

        @if (!empty($badges))
            <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                @foreach ($badges as $badge)
                    <span class="badge rounded-pill text-bg-{{ $badge['color'] }} fw-normal fs-6">
                        {{ $badge['text'] }}
                    </span>
                @endforeach
                <a href="{{ route('filings.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Clear all
                </a>
            </div>
        @endif

        <div class="row">
            {{ $filings->appends(request()->query())->links('pagination::bootstrap-5') }}
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Bibliography</th>
                        <th scope="col">Origin</th>
                        <th scope="col">Text</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filings as $filing)
                        <tr>
                            <th scope="row">{{ $filing->id }}</th>

                            <td>
                                <ul>
                                    @foreach ($filing->formatBibliography() as $entry)
                                        <li>{{ $entry['text'] }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ $filing->location }}</td>
                            <td>{{ $filing->textTruncate() }}</td>
                            <td>{{ $filing->datation }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-outline-primary btn-sm"
                                        href="{{ route('filings.show', $filing->id) }}">Details</a>

                                    @if (Auth::user()?->isAdmin())
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $filing->id }}">Elimina</button>
                                    @endif
                                </div>
                            </td>

                        </tr>


                        {{-- Logica modale cancellazione filing --}}

                        <div class="modal fade" id="deleteModal-{{ $filing->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm deletion</h5>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this filing?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('filings.destroy', $filing) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" class="btn btn-danger" value="Delete">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            {{ $filings->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
