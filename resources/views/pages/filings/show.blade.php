@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center p-5">
                <h1>Filing #{{ $filing->id }}</h1>
            </div>
        </div>

        <div class="row mb-4 g-4">

            <div class="col-4 d-flex flex-column gap-3">

                <div class="p-3 border rounded-3">
                    <h5 class="mb-3">Tags</h5>
                    @forelse($groupedTags as $category => $tags)
                        <div class="mb-1">
                            <strong>{{ ucfirst($category) }}:</strong>
                            @foreach ($tags as $tag)
                                <span class="badge bg-primary">{{ $tag->label }}</span>
                            @endforeach
                        </div>
                    @empty
                        <span class="text-muted">Nessun tag</span>
                    @endforelse
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Datation</h5>
                    <p class="mb-0">{{ $filing->datation }}</p>
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Origin</h5>
                    <p class="mb-0">{{ $filing->location }}</p>
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Bibliography</h5>
                    <ul class="mb-0 ps-3">
                        @forelse($filing->formatBibliography() as $entry)
                            <li>
                                {{ $entry['text'] }}
                                @if ($entry['image'])
                                    <a href="{{ asset('storage/' . $entry['image']) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm ms-1">View image</a>
                                @endif
                            </li>
                        @empty
                            <span class="text-muted">Nessuna edizione</span>
                        @endforelse
                    </ul>
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Religion</h5>
                    <p class="mb-0">{{ ucfirst($filing->religion) }}</p>
                    @if ($filing->is_sacred_dedication)
                        <span class="badge bg-secondary mt-1">Sacred dedication</span>
                    @endif
                </div>

            </div>

            <div class="col-8">
                <div class="p-3 border rounded-3 h-100">
                    <h5 class="mb-3">Text</h5>
                    <p class="mb-0">{{ $filing->text }}</p>
                </div>
            </div>

        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex gap-2">
                <a href="{{ route('filings.index') }}" class="btn btn-outline-primary">← Back to database</a>

                @if (Auth::user())
                    <a href="{{ route('proposals.revisions.create', $filing) }}" class="btn btn-primary">Propose a
                        revision</a>

                    @if (Auth::user()->isAdmin())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal-{{ $filing->id }}">Delete</button>
                    @endif
                @endif
            </div>
        </div>

        {{-- Modale cancellazione --}}
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

    </div>
@endsection
