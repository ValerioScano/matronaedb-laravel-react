@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="fs-4 text-secondary my-4">
            {{ __('Dashboard') }}
        </h2>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">{{ __('Admin Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Let's do some admin work, {{ Auth::user()->first_name }}</p>

                        <div class="d-flex gap-2 mb-2">
                            <a href="{{ route('my.proposals.create') }}" class="btn btn-primary">File a new inscription</a>
                            <a href="{{ route('my.proposals.index') }}" class="btn btn-primary">See your filings</a>
                        </div>

                        <div class="d-flex gap-2 mb-2">
                            <a href="{{ route('admin.proposals.pending') }}" class="btn btn-warning">Inspect proposals</a>
                        </div>

                        <div class="d-flex gap-2 mb-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-warning">Listing of users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
