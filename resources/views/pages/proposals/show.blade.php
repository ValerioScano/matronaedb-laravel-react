@extends('layouts.app')
@section('scripts')
    <script>
        document.addEventListener('keydown', function(e) {
            @if ($previousId)
                if (e.key === 'ArrowLeft') {
                    window.location.href = "{{ route('proposals.show', $previousId) }}";
                }
            @endif
            @if ($nextId)
                if (e.key === 'ArrowRight') {
                    window.location.href = "{{ route('proposals.show', $nextId) }}";
                }
            @endif
        });
    </script>
@endsection
@section('content')
        <div class="row">
            <div class="col-12 {{ $filing ? 'col-lg-6' : '' }}">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if ($previousId)
                            <a href="{{ route('proposals.show', $previousId) }}" class="btn btn-outline-primary">←
                                Previous</a>
                        @endif
                    </div>
                    <div class="col text-center p-3 p-md-5">
                        <h1>
                            <h1>
                                @if ($proposal->trashed())
                                    Deleted proposal #{{$proposal->id}} 
                                @elseif($filing)
                                    Revision on filing #{{ $filing->id }}
                                @elseif($proposal->status === 'approved' && $proposal->filing_id)
                                    Creation of filing #{{ $proposal->filing_id }}
                                @else
                                    Filing proposal #{{ $proposal->id }}
                                @endif
                            </h1>
                            <span
                                class="badge align-middle
    @if ($proposal->trashed()) bg-danger fs-3
    @elseif($proposal->status === 'approved') fs-5 bg-success
    @elseif($proposal->status === 'rejected') fs-5 bg-danger
    @else bg-warning text-dark @endif">
                                @if ($proposal->trashed())
                                    Deleted
                                @else
                                    {{ ucfirst($proposal->status) }}
                                @endif
                            </span>
                        </h1>
                    </div>
                    <div class="col-auto">
                        @if ($nextId)
                            <a href="{{ route('proposals.show', $nextId) }}" class="btn btn-outline-primary">Next →</a>
                        @endif
                    </div>
                </div>

                @if ($proposal->status === 'rejected' && $proposal->rejection_notes)
                    <div class="alert alert-danger mb-4">
                        <strong>Rejection notes:</strong> {{ $proposal->rejection_notes }}
                    </div>
                @endif

                @if ($proposal->private_notes)
                    <div class="alert alert-light" role="alert">
                        <strong>Notes from the author:</strong> {{ $proposal->private_notes }}
                    </div>
                @endif

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
                            @forelse($proposal->people as $person)
                                <div class="mb-2">
                                    <span>{{ implode(' ', array_filter([$person->praenomen, $person->nomen, $person->cognomen])) }}</span>
                                    @if ($person->TM_PER_id)
                                        <span class="text-muted small ms-1">(TM Per ID{{ $person->TM_PER_id }})</span>
                                    @endif
                                </div>
                            @empty
                                <span class="text-muted">No people recorded</span>
                            @endforelse
                        </div>

                        <div class="p-3 border rounded-3">
                            <h5 class="mb-3">External resources</h5>
                            @forelse($proposal->externalResources as $resource)
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
                            <p class="mb-0">{{ $proposal->datation }}</p>
                        </div>

                        <div class="p-3 border rounded-3">
                            <h5 class="mb-2">Origin</h5>
                            <p class="mb-0">{{ $proposal->location }}</p>
                        </div>

                        <div class="p-3 border rounded-3">
                            <h5 class="mb-2">Bibliography</h5>
                            <ul class="mb-0 ps-3">
                                @foreach ($proposal->formatBibliography() as $entry)
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
                                @endforeach
                            </ul>
                            @if ($proposal->editions->isEmpty())
                                <div class="mt-2"><span class="text-muted">Nessuna edizione</span></div>
                            @endif
                        </div>

                        <div class="p-3 border rounded-3">
                            <h5 class="mb-2">Religion</h5>
                            <p class="mb-0">{{ ucfirst($proposal->religion) }}</p>
                            @if ($proposal->is_sacred_dedication)
                                <span class="badge bg-secondary mt-1">Sacred dedication</span>
                            @endif
                        </div>


                        <div class="p-3 border rounded-3">
                            <h5 class="mb-2">Notes</h5>
                            <p class="mb-0">{{ $proposal->notes }}</p>
                            @if (!$proposal->notes)
                                <span class="text-muted">No notes</span>
                            @endif
                        </div>


                    </div>

                    <div class="col-12 col-md-8">
                        <div class="p-3 border rounded-3 mb-5 h-100">
                            <h4 class="mb-3 text-center">Text</h4>
                            <p class="mb-0">{{ $proposal->text }}</p>
                        </div>
                    </div>

                </div>

                <div class="row mb-4 g-3">
                    <div class="col-12 d-flex flex-wrap gap-2">

                        <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">← Back to proposals</a>
                        <a href="{{ route('proposals.create') }}" class="btn btn-outline-primary">+ New proposal</a>

                        @if (Auth::id() === $proposal->proposed_by && $proposal->status === 'rejected')
                            <a href="{{ route('proposals.edit', $proposal->id) }}" class="btn btn-warning">Edit
                                proposal</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $proposal->id }}">Delete proposal</button>
                        @endif

                    </div>
                    @if (Auth::user()->role === 'admin' && $proposal->status === 'pending')
                        <div class="col-12 d-flex flex-wrap gap-2">
                            <form action="{{ route('proposals.approve', $proposal) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal-{{ $proposal->id }}">Reject</button>
                            <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-warning">Modify & submit</a>
                        </div>
                    @endif

                </div>



                {{-- Modale cancellazione proposta --}}
                <div class="modal fade" id="deleteModal-{{ $proposal->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm deletion</h5>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this proposal?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('proposals.destroy', $proposal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-danger" value="Delete">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modale rigetto admin --}}
                <div class="modal fade" id="rejectModal-{{ $proposal->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm rejection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('proposals.reject', $proposal) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <p>Why do you want to reject this proposal?</p>
                                    <label for="rejection_notes" class="form-label">Reasons:</label>
                                    <textarea name="rejection_notes" id="rejection_notes" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-danger" value="Reject">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fine proposal / Inizio filing originale per confronto con proposal (admin function) --}}

            @if ($filing)
                <div class="col-12 col-lg-6 border-top border-lg-start mt-4 mt-lg-0">
                    <div class="row align-items-center">
                        <div class="col-12 text-center p-5">
                            <h1>Filing #{{ $filing->id }}</h1>
                        </div>
                    </div>

                    <div class="row mb-4 g-4 mt-5">

                        <div class="col-12 col-md-4 d-flex flex-column gap-3">

                            <div class="p-3 border rounded-3">
                                <h5 class="mb-3">Tags</h5>
                                @forelse($groupedTagsFiling as $category => $tags)
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
                                            <span class="text-muted small ms-1">(TM {{ $person->TM_PER_id }})</span>
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
                                    <li>
                                        @foreach ($filing->formatBibliography() as $entry)
                                            @if ($entry['link'])
                                                <a href="{{ $entry['link'] }}" target="_blank">{{ $entry['text'] }}</a>
                                            @else
                                                {{ $entry['text'] }}
                                            @endif
                                            @if ($entry['image'])
                                                <a href="{{ asset('storage/' . $entry['image']) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm ms-1">View image</a>
                                            @endif
                                        @endforeach
                                    </li>
                                </ul>
                                @if ($filing->editions->isEmpty())
                                    <div class="mt-2"><span class="text-muted">Nessuna edizione</span></div>
                                @endif
                            </div>

                            <div class="p-3 border rounded-3">
                                <h5 class="mb-2">Religion</h5>
                                <p class="mb-0">{{ ucfirst($filing->religion) }}</p>
                                @if ($filing->is_sacred_dedication)
                                    <span class="badge bg-secondary mt-1">Sacred dedication</span>
                                @endif
                            </div>

                            <div class="p-3 border rounded-3">
                                <h5 class="mb-2">Notes</h5>
                                <p class="mb-0">{{ $filing->notes }}</p>
                                @if (!$filing->notes)
                                    <span class="text-muted">No notes</span>
                                @endif
                            </div>

                        </div>

                        <div class="col-12 col-md-8">
                            <div class="p-3 border rounded-3 h-100">
                                <h5 class="mb-3">Text</h5>
                                <p class="mb-0">{{ $filing->text }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
@endsection
