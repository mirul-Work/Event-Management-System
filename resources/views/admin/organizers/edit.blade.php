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
<h1>Same here under development if wanna add</h1>
{{-- <h1>Edit Organizer</h1>

<form action="{{ route('admin.organizers.update', $organizer->organizer_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $organizer->name) }}" required>
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

    <button type="submit" class="btn btn-primary">Update Organizer</button>
    <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">Cancel</a>
</form> --}}
@endsection
