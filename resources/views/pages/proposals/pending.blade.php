@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Pending proposals</h1>
                <p>This is a register of all the proposals that are currently pending</p>
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
                    @foreach ($user_proposals as $user_proposal)
                        <tr>
                            <th scope="row">{{ $user_proposal->id }}</th>
                            <td>
                                <ul>
                                    @foreach ($user_proposal->formatBibliography() as $entry)
                                        <li>{{ $entry['text'] }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ $user_proposal->location }}</td>
                            <td>{{ $user_proposal->textTruncate() }}</td>
                            <td>{{ $user_proposal->datation }}</td>
                            <td><a href="{{ route('proposals.show', $user_proposal->id) }}" class="btn btn-primary">See
                                    details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $user_proposals->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
