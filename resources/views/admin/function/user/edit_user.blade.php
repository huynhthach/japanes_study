@extends('admin.layout.app')

@section('content')
    <div class="container">
        <h1>Edit User</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="Username">Username:</label>
                <input type="text" class="form-control" name="Username" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="Email">Email:</label>
                <input type="email" class="form-control" name="Email" value="{{ $user->email }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
