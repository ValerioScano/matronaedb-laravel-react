@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row text-center flex-column py-5">
            <div class="col-12">
                <h1>Users' list</h1>
                <p>This is list displays all the users</p>
            </div>
        </div>

        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Email</th>
                        <th scope="col">Account activation</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>

                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->email_verified_at }}</td>
                            <td>
                                <button type="button"
                                    class="btn {{ $user->role === 'admin' ? 'btn-warning' : 'btn-success' }}"
                                    data-bs-toggle="modal" data-bs-target="#roleModal-{{ $user->id }}">
                                    {{ $user->role === 'admin' ? 'Revoke admin' : 'Make admin' }}
                                </button>

                                {{-- Logica modale modifica ruolo --}}
                                <div class="modal fade" id="roleModal-{{ $user->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm change of role</h5>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to modify the role of {{ $user->first_name }}
                                                {{ $user->last_name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('users.updateRole', $user) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-warning">
                                                        {{ $user->role === 'admin' ? 'Revoke admin' : 'Make admin' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $user->id }}">
                                    Delete account
                                </button>

                                {{-- Logica modale cancellazione user's account --}}
                                <div class="modal fade" id="deleteModal-{{ $user->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm deletion</h5>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this account?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="submit" class="btn btn-warning" value="Delete">
                                                </form>
                                                <form action="{{ route('users.destroyWithRecords', $user) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="submit" class="btn btn-danger"
                                                        value="Delete with all records">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
