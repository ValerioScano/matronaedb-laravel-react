@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-12 text-center">
                <h1>Welcome to MatronaeDB</h1>
            </div>
        </div>

        <div class="row py-3 text-center">
            <div class="col-12">
                <p class="mx-4">Inscriptions of female civic euergetism in the provinces of the Roman Empire from the
                    early Imperial age to the rise of Christianity</p>
                <p class="mx-4">A project conceived for Alice Cicarelli, designed by Valerio Scano</p>
                <a href="{{ route('filings.index') }}" class="btn btn-outline-primary">Browse the database</a>
            </div>
        </div>

        <div class="row py-5 text-center">
            <div class="col-12">
                <p>MatronaeDB is free to use, however we have to deal with hosting costs. If you'd like to contribute, even
                    with 1€, it would help us in our journey to digitization <a href="https://paypal.me/vscano00"
                        class="btn btn-outline-primary btn-sm">Buy us a coffee! <i class="bi bi-cup-hot-fill"></i></a></p>
            </div>
        </div>
    </div>
@endsection
