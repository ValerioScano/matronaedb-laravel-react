@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Your proposals</h1>
                <p>This is the register of your proposals</p>
            </div>
        </div>

        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Status</th>
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
                            <td>{{$user_proposal->status}}</td>
                            <td>
                                @foreach ($user_proposal->editions as $edition)
                                    {{ $edition->corpus }} {{ $edition->number_inscription }}
                                @endforeach
                            </td>

                            <td>{{ $user_proposal->region }} {{ $user_proposal->province }}</td>
                            <td>{{ $user_proposal->text }}</td>
                            <td>{{ $user_proposal->min_year }} - {{ $user_proposal->max_year }}</td>
                            <td><a href="{{route('my.proposals.show', $user_proposal->id)}}" class="btn btn-primary">See details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $user_proposals->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection