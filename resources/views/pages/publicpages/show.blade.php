@extends('layouts.app')
@section('content')
    <div class="text-center p-5">
        <h1>Details on filing w/ id {{ $filing->id }}</h1>
    </div>

    <div class="row">
        <div class="col-12">
            @foreach ($filing->tags as $tag)
                <p class="text-center">{{ $tag->category }}: {{ $tag->label }}
                <p>
            @endforeach
        </div>
    </div>


    <div class="row">
        <div class="col-12 text-center px-5">
            <p>{{ $filing->text }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 px-5">
            <button class="btn btn-primary"> <a href="{{ route('my.revisions.create', $filing) }}" class="text-white">Propose a
                revision</a></button>
        </div>
    </div>
@endsection
