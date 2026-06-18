@extends('layouts.app')
@section('content')
        <div class="row my-5 text-center">
            <div class="col-12">
                <h1>Welcome to MatronaeDB</h1>
                <p class="mx-4 lh-base">Inscriptions of female civic euergetism in the provinces of the Roman Empire from the
                    early Imperial age to the rise of Christianity. <br> A project conceived for Alice Cicarelli, designed by Valerio Scano</p>
                
                <a href="{{ route('filings.index') }}" class="btn btn-outline-dark fw-semibold mt-4">Browse the database</a>
            </div>
        </div>

        <div class="row py-3 text-center border-top border-bottom align-items-center">
            <div class="col-6">
                <img src="/img/matrona.jpg" alt="matrona romana" class="img-fluid" style="max-height: 400px">
            </div>

            <div class="col-6">
                <div class="row g-5">
                    <h3 class="fw-semibold">As of today we've registered:</h3>
                    <div class="col-4">
                        <h2>{{ $filingsCount }}</h2>
                        <p class="text-muted">Filings</p>
                    </div>
                    <div class="col-4">
                        <h2>{{ $provincesCount }}/66</h2>
                        <p class="text-muted">Provinces</p>
                    </div>
                    <div class="col-4">
                        <h2>{{ $peopleCount }}</h2>
                        <p class="text-muted">People</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-5 text-center">
            <div class="col-12">
                <p class="mx-4">MatronaeDB is free to use, however we have to deal with hosting costs. If you'd like
                    to contribute, even with 1€, it would help us in our journey to digitization.</p>
                <a href="https://paypal.me/vscano00" class="btn btn-outline-dark fw-semibold mt-4">
                    Buy us a coffee! <i class="bi bi-cup-hot-fill"></i>
                </a>
            </div>
        </div>
@endsection
