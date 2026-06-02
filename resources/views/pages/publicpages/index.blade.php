@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Explore the database</h1>
                <p>Apply filters or browse through the entire database</p>
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
                                @foreach ($filing->editions as $edition)
                                    {{ $edition->corpus }} {{ $edition->number_inscription }}
                                @endforeach
                            </td>

                            <td>{{ $filing->region }} {{ $filing->province }}</td>
                            <td>{{ $filing->text }}</td>
                            <td>{{ $filing->min_year }} - {{ $filing->max_year }}</td>
                            <td><a class="btn btn-outline-primary" href="{{route('filings.show', $filing->id)}}">Show details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $filings->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
