@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Add a new filing</h1>
            </div>
        </div>

        <form action="{{ route('proposals.revisions.store', $filing) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row align-items-center gx-5 p-4 border border-primary-subtle border-opacity-25 rounded-4">

                {{-- Testo iscrizione --}}
                <h3>Inscription's details</h3>
                <div class="col-12 my-3">
                    <label for="text" class="form-label">Add the inscription's text <span
                            class="text-danger">*</span></label>
                    <textarea name="text" class="form-control" id="text" aria-describedby="text-help" required>{{ old('text', $filing->text) }}</textarea>
                    <div id="text-help" class="form-text">Use conventional transcribing system to register the text
                    </div>
                </div>

                {{-- Localizzazione iscrizione --}}

                <div class="col-4 mb-3">
                    <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                    <input name="region" type="text" class="form-control" id="region" required autocomplete="off"
                        value="{{ old('region', $filing->region) }}">
                </div>

                <div class="col-4 mb-3">
                    <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                    <input name="province" type="text" class="form-control" id="province" required
                        value="{{ old('province', $filing->province) }}">
                </div>

                <div class="col-4 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input name="city" type="text" class="form-control" id="city"
                        value="{{ old('city', $filing->city) }}">
                </div>

                {{-- datazione iscrizione --}}
                <div class="col-4 mb-3">
                    <label for="min_year" class="form-label">Earliest datation</label>
                    <input name="min_year" type="number" class="form-control" id="min_year"
                        value="{{ old('min_year', $filing->min_year) }}">
                </div>

                <div class="col-4 mb-3">
                    <label for="max_year" class="form-label">Latest datation</label>
                    <input name="max_year" type="number" class="form-control" id="max_year"
                        value="{{ old('max_year', $filing->max_year) }}">
                </div>

                <div class="col-4 mb-3 form-check">
                    <input name="is_certain_date" type="checkbox" class="form-check-input" id="is_certain_date"
                        @checked(old('is_certain_date', $filing->is_certain_date))>
                    <label for="is_certain_date" class="form-check-label">Is the datation sure within a 50 years
                        range?</label>
                </div>

                {{-- Religione --}}

                <div class="col-6 mb-3 form-check">
                    <input name="is_sacred_dedication" type="checkbox" class="form-check-input" id="is_sacred_dedication"
                        @checked(old('is_sacred_dedication', $filing->is_sacred_dedication))>
                    <label for="is_sacred_dedication" class="form-check-label">Is the inscription a sacred
                        dedication?</label>
                </div>

                <div class="col-6 mb-3">
                    <label for="religion">Religion:</label>
                    <select name="religion" class="form-select" id="religion">
                        <option value="uncertain" @selected(old('religion', $filing->religion) === 'uncertain')>Uncertain</option>
                        <option value="pagan" @selected(old('religion', $filing->religion) === 'pagan')>Pagan</option>
                        <option value="christian" @selected(old('religion', $filing->religion) === 'christian')>Christian</option>
                    </select>
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
                    <textarea name="private_notes" id="private_notes" class="form-control"></textarea>
                </div>
            </div>

            {{-- Sezione edizioni --}}

            <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
                <h4>Editions</h4>
                <div class="col-12 my-3">
                    
                    <div id="editions-container" data-editions='@json($filing->editions)'>
                        {{-- Edizioni recuperate qui --}}
                    </div>

                    <button type="button" class="btn btn-outline-primary" id="add-edition-btn">Add edition</button>
                </div>
            </div>

            {{-- Sezione persone --}}

            <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
                <h4>People</h4>
                <div class="col-12 my-3">
                    <div id="people-container" data-people='@json($filing->people)'>
                        {{-- Persone recuperate qui --}}
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="add-person-btn">Add person</button>
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
                                                <input type="checkbox" class="form-check-input" id="tag_{{ $pair['base']->id }}"
                                                    name="tags[]" value="{{ $pair['base']->id }}" @checked(in_array($pair['base']->id, old('tags', $filing->tags->pluck('id')->toArray())))>
                                                <label class="form-check-label" for="tag_{{ $pair['base']->id }}">{{ $pair['base']->label }}</label>
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            @if ($pair['uncertain'])
                                                <div class="form-check mb-0">
                                                    <input type="checkbox" class="form-check-input" id="tag_{{ $pair['uncertain']->id }}"
                                                        name="tags[]" value="{{ $pair['uncertain']->id }}" @checked(in_array($pair['uncertain']->id, old('tags', $filing->tags->pluck('id')->toArray())))>
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

            <div class="col-12 mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
@endsection
