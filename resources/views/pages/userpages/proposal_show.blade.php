@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mt-5 mb-3">
                <h1>Details on {{$proposal->status}} proposal id {{ $proposal->id }}</h1>
            </div>
            @if ($proposal->status === 'rejected' && $proposal->rejection_notes)
                <div class="alert alert-danger mt-3">
                    <strong>Rejection notes:</strong> {{ $proposal->rejection_notes }}
                </div>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="row my-3 text-center">
            <div class="col-3">
                @forelse($groupedTags as $category=>$tags)
                    <div>
                        <strong>{{ $category }}:</strong>
                        @foreach ($tags as $tag)
                            <span class="badge bg-primary">{{ $tag->label }}</span>
                        @endforeach
                    </div>
                @empty
                    <span class="text-muted">Nessun tag</span>
                @endforelse
            </div>

            <div class="col-3">
                <div>
                    <p>Datation: {{ $proposal->min_year }} - {{ $proposal->max_year }}</p>
                </div>
            </div>

            <div class="col-3">
                <p>{{ $proposal->region }}, {{ $proposal->province }}, {{ $proposal->city }}</p>
            </div>

            <div class="col-3">
                @if ($proposal->is_sacred_dedication)
                    <span>Inscription is sacred dedication.</span>
                @endif
                <span>Religion is {{ $proposal->religion }}</span>
            </div>
        </div>

        <div class="row my-3 py-2 gap-5 flex-nowrap">
            <div class="col-4 text-center border border-primary-subtle border-opacity-25 rounded-4 p-3">
                <ul>
                    @forelse($proposal->editions as $edition)
                        <li>{{ $edition->corpus }}, {{ $edition->volume }} {{ $edition->number_inscription }}
                            @if ($edition->edition_image)
                                <div>
                                    <a href="{{ asset('storage/' . $edition->edition_image) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        View image
                                    </a>
                                </div>
                            @endif
                        </li>
                    @empty
                        <span class="text-muted">Nessun tag</span>
                    @endforelse
                </ul>
            </div>
            <div class="col-8 text-center border border-primary-subtle border-opacity-25 rounded-4 p-3">
                <p>{{ $proposal->text }}</p>
            </div>
        </div>

        <div class="row my-3 py-2 gap-5 flex-nowrap">
            <div class="col-12">
                <strong>Notes on filing:</strong>
                <p>{{ $proposal->notes }}</p>
            </div>
        </div>

        {{-- Buttons only for the proposer --}}
        @if (Auth::id() === $proposal->proposed_by && $proposal->status === 'rejected')
            <div class="row my-3">
                <div class="col-2"><a href="{{ route('my.proposals.edit', $proposal->id) }}"
                        class="btn btn-warning">Modify
                        proposal</a>
                </div>

                <div class="col-2">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ $proposal->id }}">
                        Elimina
                    </button>
                </div>
            </div>
        @endif

        {{-- Buttons only for admins --}}
        @if (Auth::user()->role === 'admin')
            <div class="row my-3">
                <div class="row my-3">
                    <div class="col-2">
                        <form action="{{ route('admin.proposals.approve', $proposal) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Approve proposal</button>
                        </form>
                    </div>

                    <div class="col-2">
                        <button class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#rejectModal-{{ $proposal->id }}">Reject proposal</button>
                    </div>
                </div>
            </div>
        @endif

    </div>


    {{-- Logica modale cancellazione user's own proposal --}}
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
                    <form action="{{ route('my.proposals.destroy', $proposal) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Logica modale cancellazione admin's rejection --}}

    <div class="modal fade" id="rejectModal-{{ $proposal->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.proposals.reject', $proposal) }}" method="POST">
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
