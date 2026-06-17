@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Edit tag pair</h1>
                <p>Editing the base tag will update its uncertain variant (?) automatically.</p>
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

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="border rounded p-4">
                    <form action="{{ route('tags.update', $tag) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}"
                                class="form-control @error('name') is-invalid @enderror">
                            <div class="form-text">
                                Uncertain variant:
                                @if ($uncertainTag)
                                    <strong>{{ $uncertainTag->name }}</strong> — will be updated automatically.
                                @else
                                    <span class="text-warning">No uncertain variant found for this tag.</span>
                                @endif
                                <div class="form-text">
                                    <p>Database standards require the name to be in lowercase, the space must be replaced
                                        with an underscore (ex. donum_facio). No numbers should be allowed.</p>
                                </div>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                            <input type="text" id="label" name="label" value="{{ old('label', $tag->label) }}"
                                class="form-control @error('label') is-invalid @enderror">
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" id="category" name="category"
                                value="{{ old('category', $tag->category) }}"
                                class="form-control @error('category') is-invalid @enderror" list="category-suggestions">
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
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <a href="{{ route('tags.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
