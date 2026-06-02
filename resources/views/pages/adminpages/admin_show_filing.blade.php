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
            <button class="btn btn-primary"> <a href="{{ route('my.revisions.create', $filing) }}" class="text-white">Propose
                    a
                    revision</a></button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                data-bs-target="#deleteModal-{{ $filing->id }}">Elimina</button>
        </div>
    </div>
@endsection

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
                <form action="{{ route('admin.filings.destroy', $filing) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
</div>
