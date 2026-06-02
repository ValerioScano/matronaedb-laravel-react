@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Add a new filing</h1>
            </div>
        </div>


        <div class="container">
            <form action="{{ route('my.revisions.store', $filing) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row align-items-center gx-5 p-4 border border-primary-subtle border-opacity-25 rounded-4">

                    {{-- Testo iscrizione --}}
                    <h3>Inscription's details</h3>
                    <div class="col-12 my-3">
                        <label for="text" class="form-label">Add the inscription's text</label>
                        <textarea name="text" class="form-control" id="text" aria-describedby="text-help" required>{{ $filing->text }}</textarea>
                        <div id="text-help" class="form-text">Use conventional transcribing system to register the text
                        </div>
                    </div>

                    {{-- Localizzazione iscrizione --}}

                    <div class="col-4 mb-3">
                        <label for="region" class="form-label">Region</label>
                        <input name="region" type="text" class="form-control" id="region" required autocomplete="off"
                            value="{{ $filing->region }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="province" class="form-label">Province</label>
                        <input name="province" type="text" class="form-control" id="province" required
                            value="{{ $filing->province }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input name="city" type="text" class="form-control" id="city"
                            value="{{ $filing->city }}">
                    </div>

                    {{-- datazione iscrizione --}}
                    <div class="col-4 mb-3">
                        <label for="min_year" class="form-label">Earliest datation</label>
                        <input name="min_year" type="number" class="form-control" id="min_year"
                            value="{{ $filing->min_year }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="max_year" class="form-label">Latest datation</label>
                        <input name="max_year" type="number" class="form-control" id="max_year"
                            value="{{ $filing->max_year }}">
                    </div>

                    <div class="col-4 mb-3 form-check">
                        <input name="is_certain_date" type="checkbox" class="form-check-input" id="is_certain_date"
                            @checked($filing->is_certain_date)>
                        <label for="is_certain_date" class="form-check-label">Is the datation sure within a 50 years
                            range?</label>
                    </div>

                    {{-- Religione --}}

                    <div class="col-6 mb-3 form-check">
                        <input name="is_sacred_dedication" type="checkbox" class="form-check-input"
                            id="is_sacred_dedication" @checked($filing->is_sacred_dedication)>
                        <label for="is_sacred_dedication" class="form-check-label">Is the inscription a sacred
                            dedication?</label>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="religion">Religion:</label>
                        <select name="religion" class="form-select" id="religion">
                            <option value="uncertain" @selected($filing->religion === 'uncertain')>Uncertain</option>
                            <option value="pagan" @selected($filing->religion === 'pagan')>Pagan</option>
                            <option value="christian" @selected($filing->religion === 'christian')>Christian</option>
                        </select>
                    </div>

                    {{-- Commenti --}}

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Do you want to add a public comment on this
                            inscription?</label>
                        <textarea name="notes" class="form-control" id="notes" aria-describedby="notes-help">{{ $filing->notes }}</textarea>
                        <div id="notes-help" class="form-text">This comment should be helpful to the person that wants
                            to
                            consult your filing </div>
                    </div>
                </div>

                {{-- Sezione edizioni --}}

                <div class="row align-items-center gx-5 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
                    <h4>Editions</h4>
                    <div class="col-12 my-3">
                        <div id="editions-container">
                            @foreach ($filing->editions as $i => $edition)
                                <div class="row mb-3 edition-row align-items-center border border-warning-subtle rounded-4 p-3"
                                    id="edition-{{ $i }}">
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Corpus or Journal</label>
                                        <input type="text" name="editions[{{ $i }}][corpus]"
                                            class="form-control" value="{{ $edition->corpus }}">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Volume in arabic numbers</label>
                                        <input type="number" name="editions[{{ $i }}][volume]"
                                            class="form-control" value="{{ $edition->volume }}">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Inscription number</label>
                                        <input type="number" name="editions[{{ $i }}][number_inscription]"
                                            class="form-control" value="{{ $edition->number_inscription }}">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Publication year</label>
                                        <input type="number" name="editions[{{ $i }}][publication_year]"
                                            class="form-control" value="{{ $edition->publication_year }}">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Corpus page</label>
                                        <input type="number" name="editions[{{ $i }}][corpus_page]"
                                            class="form-control" value="{{ $edition->corpus_page }}">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Author's last name</label>
                                        <input type="text" name="editions[{{ $i }}][last_name_author]"
                                            class="form-control" value="{{ $edition->last_name_author }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edition_image">Immagine edizione a stampa</label>
                                        @if($edition->edition_image)
                                            <div class="mb-2">
                                                <p class="text-muted small">Current file: 
                                                    <a href="{{asset('storage/' . $edition->edition_image) }}" target="_blank" class="text-decoration-none">View</a>
                                                </p>
                                            </div>
                                        @endif
                                        <input type="file" name="editions[{{$i}}][edition_image]" id="edition_image_{{$i}}"
                                            class="form-control">
                                        <small class="form-text text-muted">Upload a new file to replace the existing one</small>
                                    </div>
                                    {{-- hidden per passare l'id dell'edizione esistente --}}
                                    <input type="hidden" name="editions[{{ $i }}][id]"
                                        value="{{ $edition->id }}">
                                    <div class="col-3 mb-3 ms-5">
                                        <button type="button" class="btn btn-danger remove-edition-btn">Delete
                                            edition</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="add-edition-btn">Add edition</button>
                    </div>
                </div>

                {{-- Sezione tags  --}}

                <div
                    class="row align-items-center gx-5 gap-3 my-2 p-4 border border-primary-subtle border-opacity-25 rounded-4">
                    <h4 class="col-12">Tags</h4>

                    @foreach ($groupedTags as $category => $tags)
                        <fieldset class="col-4 border border-warning-subtle rounded-4 d-flex flex-wrap gap-2">
                            <legend>{{ $category }}</legend>

                            @foreach ($tags as $tag)
                                <div>
                                    <input type="checkbox" id="tag_{{ $tag->id }}" name="tags[]"
                                        value="{{ $tag->id }}" @checked($filing->tags->contains($tag->id))>
                                    <label for="tag_{{ $tag->id }}">{{ $tag->label }}</label>
                                </div>
                            @endforeach
                        </fieldset>
                    @endforeach
                </div>


                <div class="col-12 mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

    </div>
@endsection
