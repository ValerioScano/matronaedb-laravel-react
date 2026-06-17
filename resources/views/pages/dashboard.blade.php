@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="fs-4 text-secondary my-4">
            {{ __('Dashboard') }}
        </h2>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">{{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Let's get started, {{ Auth::user()->first_name }}</p>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('proposals.create') }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-1"></i> File a new inscription
                            </a>
                            <a href="{{ route('proposals.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-folder2-open me-1"></i> See your filings
                            </a>
                        </div>

                        @if (Auth::user()->isAdmin())
                            <hr class="mb-3">
                            <p class="text-secondary small text-uppercase fw-semibold mb-2">Admin</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('proposals.pending') }}" class="btn btn-warning">
                                    <i class="bi bi-hourglass-split me-1"></i> Inspect proposals
                                </a>
                                <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-tags me-1"></i> Manage tags
                                </a>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-people me-1"></i> Manage users
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
