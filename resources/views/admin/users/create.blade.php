@extends('layouts/dashboard-admin')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('admin.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Admin Dashboard</title>
@endsection


@section('role')
<p class="text-center fw-bold">{{ Auth::user()->name }} (Admin)</p>
@endsection
@section('side-bar-ul')


@endsection


@section('content-dashboard')

<h1>Create New User</h1>

<form action="{{ route('admin.user.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        @error('email')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" required>
        @error('password')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="role">Role</label>
        <select name="role" id="role" class="form-control">
            <option value="admin">Admin</option>
            <option value="organizer">Organizer</option>
            <option value="attendee">Attendee</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Create User</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
