@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12 text-center p-5">
                <h1>Proposal #{{ $proposal->id }}
                    <span
                        class="badge 
                        @if ($proposal->status === 'approved') bg-success
                        @elseif($proposal->status === 'rejected') bg-danger
                        @else bg-warning text-dark @endif fs-6 align-middle">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </h1>
            </div>
        </div>

        @if ($proposal->status === 'rejected' && $proposal->rejection_notes)
            <div class="alert alert-danger mb-4">
                <strong>Rejection notes:</strong> {{ $proposal->rejection_notes }}
            </div>
        @endif

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
                    <p class="mb-0">{{ $proposal->datation }}</p>
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Origin</h5>
                    <p class="mb-0">{{ $proposal->location }}</p>
                </div>

                <div class="p-3 border rounded-3">
                    <h5 class="mb-2">Bibliography</h5>
                    <ul class="mb-0 ps-3">
                        @forelse($proposal->formatBibliography() as $entry)
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

                @if ($proposal->notes)
                    <div class="p-3 border rounded-3">
                        <h5 class="mb-2">Notes</h5>
                        <p class="mb-0">{{ $proposal->notes }}</p>
                    </div>
                @endif

            </div>

            <div class="col-8">
                <div class="p-3 border rounded-3 mb-5 h-100">
                    <h4 class="mb-3 text-center">Text</h4>
                    <p class="mb-0">{{ $proposal->text }}</p>
                </div>
            </div>

        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex flex-wrap gap-2">

                <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">← Back to proposals</a>
                <a href="{{ route('proposals.create') }}" class="btn btn-outline-primary">+ New proposal</a>

                @if (Auth::id() === $proposal->proposed_by && $proposal->status === 'rejected')
                    <a href="{{ route('proposals.edit', $proposal->id) }}" class="btn btn-warning">Edit proposal</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ $proposal->id }}">Delete proposal</button>
                @endif

                @if (Auth::user()->role === 'admin')
                    <form action="{{ route('proposals.approve', $proposal) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <button class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#rejectModal-{{ $proposal->id }}">Reject</button>
                @endif

            </div>
        </div>

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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-danger" value="Reject">
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
