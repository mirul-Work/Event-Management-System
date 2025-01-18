@extends('layouts/dashboard-organizer')

@section('dashboard-return')
<a class="navbar-brand ms-3" href="{{route('organizer.dashboard')}}">Event Management System</a>

@endsection
@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('role')
{{-- <p class="text-center fw-bold">Organizer</p> --}}
<p class="text-center fw-bold">{{ Auth::user()->name }} (Organizer)</p>

@endsection

@section('side-bar-ul')


@endsection

@section('content-dashboard')
<h1>Edit User</h1>

<form action="{{ route('organizer.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password (leave blank to keep the current password):</label>
        <input type="password" name="password" id="password" class="form-control">
        @error('password')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-warning">Update User</button>
    <a href="{{ route('organizer.users.index') }}" class="btn btn-primary">Cancel</a>
</form>
@endsection
