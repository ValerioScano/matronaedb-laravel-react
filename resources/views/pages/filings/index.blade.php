@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Browse the database</h1>
                <p>Apply filters or browse through the entire database 
                    @if (Auth::user()?->isAdmin())with admin privileges @endif</p>
            </div>
        </div>

        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Bibliography</th>
                        <th scope="col">Origin</th>
                        <th scope="col">Text</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filings as $filing)
                        <tr>
                            <th scope="row">{{ $filing->id }}</th>

                            <td>
                                <ul>
                                    @foreach ($filing->formatBibliography() as $entry)
                                        <li>{{ $entry['text'] }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ $filing->location}}</td>
                            <td>{{ $filing->textTruncate() }}</td>
                            <td>{{ $filing->datation}}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-outline-primary"
                                        href="{{ route('filings.show', $filing->id) }}">Show details</a>

                                    @if (Auth::user()?->isAdmin())
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $filing->id }}">Elimina</button>
                                    @endif
                                </div>
                            </td>

                        </tr>


                        {{-- Logica modale cancellazione filing --}}

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
                    @endforeach
                </tbody>
            </table>
            {{ $filings->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
