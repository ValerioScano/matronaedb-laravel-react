@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-center align-items-center flex-column pt-5">
            <h1>Welcome to MatronaeDB</h1>
</div>
<div class="mt-5 d-flex justify-content-center align-items-center flex-column">
            <p class="mx-4 text-center">Inscriptions of female civic euergetism in the provinces of the Roman Empire from the early Imperial age to the rise of Christianity</p>
            <p class="mx-4 text-center">A project conceived for Alice Cicarelli, designed by Valerio Scano</p>
            <a href="{{ route('filings.index') }}" class="btn btn-outline-primary">Browse the database</a>
        </div>

@endsection