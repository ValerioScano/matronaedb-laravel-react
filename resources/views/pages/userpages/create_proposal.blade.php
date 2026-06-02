@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Add a new filing</h1>
            </div>
        </div>


        <div class="container">
            <form action="{{ route('my.proposals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center gx-5 p-4 border border-primary-subtle border-opacity-25 rounded-4">

                    {{-- Testo iscrizione --}}
                    <h3>Inscription's details</h3>
                    <div class="col-12 my-3">
                        <label for="text" class="form-label">Add the inscription's text</label>
                        <textarea name="text" class="form-control" style="height:auto" id="text" aria-describedby="text-help" required></textarea>
                        <div id="text-help" class="form-text">Use conventional transcribing system to register the text
                        </div>
                    </div>

                    {{-- Localizzazione iscrizione --}}

                    <div class="col-4 mb-3">
                        <label for="region" class="form-label">Region</label>
                        <input name="region" type="text" class="form-control" id="region" required
                            autocomplete="off">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="province" class="form-label">Province</label>
                        <input name="province" type="text" class="form-control" id="province" required>
                    </div>

                    <div class="col-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input name="city" type="text" class="form-control" id="city">
                    </div>

                    {{-- datazione iscrizione --}}
                    <div class="col-4 mb-3">
                        <label for="min_year" class="form-label">Earliest datation</label>
                        <input name="min_year" type="number" class="form-control" id="min_year">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="max_year" class="form-label">Latest datation</label>
                        <input name="max_year" type="number" class="form-control" id="max_year">
                    </div>

                    <div class="col-4 mb-3 form-check">
                        <input name="is_certain_date" type="checkbox" class="form-check-input" id="is_certain_date">
                        <label for="is_certain_date" class="form-check-label">Is the datation sure within a 50 years
                            range?</label>
                    </div>

                    {{-- Religione --}}

                    <div class="col-6 mb-3 form-check">
                        <input name="is_sacred_dedication" type="checkbox" class="form-check-input"
                            id="is_sacred_dedication">
                        <label for="is_sacred_dedication" class="form-check-label">Is the inscription a sacred
                            dedication?</label>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="religion">Religion:</label>
                        <select name="religion" class="form-select" id="religion">
                            <option value="uncertain" selected>Uncertain</option>
                            <option value="pagan">Pagan</option>
                            <option value="christian">Christian</option>
                        </select>
                    </div>

                    {{-- Commenti --}}

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Do you want to add a public comment on this
                            inscription?</label>
                        <textarea name="notes" class="form-control" id="notes" aria-describedby="notes-help"></textarea>
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
                            {{-- Le edizioni verranno aggiunte qui --}}
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
                                        value="{{ $tag->id }}">
                                    <label for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
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
