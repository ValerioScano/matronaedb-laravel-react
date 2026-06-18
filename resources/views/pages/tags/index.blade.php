@extends('layouts.app')

@section('content')
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Tags</h1>
                <p>Manage all tags. Each tag is displayed paired with its uncertain variant (?).</p>
                <p><i class="bi bi-exclamation-triangle"></i> Attention: editing, removing or adding tags means that all the filings must be updated accordingly. Don't modify lightly <i class="bi bi-exclamation-triangle"></i></p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('tags.create') }}" class="btn btn-primary">Add tag pair</a>
            </div>
        </div>

        @forelse ($tagPairs as $category => $pairs)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-uppercase text-muted mb-2">{{ $category }}</h5>
                    <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Uncertain variant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pairs as $pair)
                                <tr>
                                    <td>{{ $pair['base']->name }}</td>
                                    <td>{{ $pair['base']->label }}</td>
                                    <td>
                                        @if ($pair['uncertain'])
                                            {{ $pair['uncertain']->name }}
                                        @else
                                            <span class="text-muted fst-italic">missing</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tags.edit', $pair['base']) }}" class="btn btn-sm btn-warning">Edit</a>

                                        <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $pair['base']->id }}">
                                            Delete
                                        </button>

                                        <div class="modal fade" id="deleteModal-{{ $pair['base']->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm deletion</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the tag pair
                                                        <strong>{{ $pair['base']->name }}</strong>
                                                        @if ($pair['uncertain'])
                                                            and <strong>{{ $pair['uncertain']->name }}</strong>
                                                        @endif
                                                        ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('tags.destroy', $pair['base']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="row">
                <div class="col-12">
                    <p class="text-muted text-center">No tags found. <a href="{{ route('tags.create') }}">Create the first one.</a></p>
                </div>
            </div>
        @endforelse
@endsection
