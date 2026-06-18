@extends('layouts.app')

@section('content')
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Create tag pair</h1>
                <p>Filling this form will create two tags at once: the base tag and its uncertain variant (?).</p>
                <p><i class="bi bi-exclamation-triangle"></i> Attention: editing, removing or adding tags means that all the
                    filings must be updated accordingly. Don't modify lightly <i class="bi bi-exclamation-triangle"></i></p>

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

        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="border rounded p-4">
                    <form action="{{ route('tags.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="e.g. votive">
                            <div class="form-text">The uncertain variant <strong>name?</strong> will be created
                                automatically.
                                <p>Database standards require the name to be in lowercase, the space must be replaced with
                                    an underscore (ex. donum_facio). No numbers should be allowed.</p>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                            <input type="text" id="label" name="label" value="{{ old('label') }}"
                                class="form-control @error('label') is-invalid @enderror" placeholder="e.g. Votive">
                            <div class="form-text">Human-readable display label. The uncertain variant will append
                                <strong>?</strong> automatically.</div>
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" id="category" name="category" value="{{ old('category') }}"
                                class="form-control @error('category') is-invalid @enderror" list="category-suggestions"
                                placeholder="e.g. typology">
                            <datalist id="category-suggestions">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create tag pair</button>
                            <a href="{{ route('tags.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
