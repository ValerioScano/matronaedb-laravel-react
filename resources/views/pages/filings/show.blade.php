@extends('layouts.app')
@section('scripts')
    <script>
        document.addEventListener('keydown', function(e) {
            @if ($previousId)
                if (e.key === 'ArrowLeft') {
                    window.location.href = "{{ route('filings.show', $previousId) }}";
                }
            @endif
            @if ($nextId)
                if (e.key === 'ArrowRight') {
                    window.location.href = "{{ route('filings.show', $nextId) }}";
                }
            @endif
        });
    </script>
@endsection

@section('content')
        <div class="row align-items-center">
            <div class="col-auto">
                @if ($previousId)
                    <a href="{{ route('filings.show', $previousId) }}" class="btn btn-outline-primary">← Previous</a>
                @endif
            </div>
            <div class="col text-center p-3 p-md-5">
                <h1>Filing #{{ $filing->id }} @if ($filing->trashed())
                        <span class="badge bg-danger">Deleted</span>
                    @endif
                </h1>
            </div>
            <div class="col-auto">
                @if ($nextId)
                    <a href="{{ route('filings.show', $nextId) }}" class="btn btn-outline-primary">Next →</a>
                @endif
            </div>
        </div>

        <div class="row mb-4 g-4">

            <div class="col-12 col-md-4 d-flex flex-column gap-3">

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
                    <h5 class="mb-3">People</h5>
                    @forelse($filing->people as $person)
                        <div class="mb-2">
                            <span>{{ implode(' ', array_filter([$person->praenomen, $person->nomen, $person->cognomen])) }}</span>
                            @if ($person->TM_PER_id)
                                <span class="text-muted small ms-1">(TM Per ID {{ $person->TM_PER_id }})</span>
                            @endif
                        </div>
                    @empty
                        <span class="text-muted">No people recorded</span>
                    @endforelse
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-3">External resources</h5>
                    @forelse($filing->externalResources as $resource)
                        <a href="{{ $resource->link }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-outline-secondary btn-sm me-1 mb-2">
                            {{ $resource->name ?? $resource->link }}
                        </a>
                    @empty
                        <span class="text-muted">No external resources</span>
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
                                @if ($entry['link'])
                                    <a href="{{ $entry['link'] }}" target="_blank">{{ $entry['text'] }}</a>
                                @else
                                    {{ $entry['text'] }}
                                @endif


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

            <div class="col-12 col-md-8">
                <div class="p-3 border rounded-3 h-100">
                    <h5 class="mb-3">Text</h5>
                    <p class="mb-0">{{ $filing->text }}</p>
                </div>
            </div>

            <div class="mt-3">
                <p>Proposed by {{ $filing->proposedBy->last_name }} on {{ $filing->updated_at->format('d/m/Y') }},
                    approved by {{ $filing->ApprovedBy->last_name }}</p>
                </p>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('filings.destroy', $filing) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Why do you want to delete this filing?</p>
                            <label for="deletion_notes" class="form-label">Reasons:</label>
                            <textarea name="deletion_notes" id="deletion_notes" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-danger" value="Delete">
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
