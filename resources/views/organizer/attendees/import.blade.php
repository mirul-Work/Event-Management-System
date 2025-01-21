@extends('layouts/dashboard-organizer')

@section('dashboard-return')
    <a class="navbar-brand ms-3 " href="{{ route('organizer.dashboard') }}">Event Management
        System</a>
@endsection

@section('title')
    <title>Organizer Dashboard</title>
@endsection

@section('role')
    <p class="text-center font-bold text-gray-700">{{ Auth::user()->name }} </p>
@endsection

@section('side-bar-ul')
    <!-- Sidebar content for organizer -->
@endsection

@section('content-dashboard')
@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('info'))
<div class="alert alert-info">{{ session('info') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('organizer.attendees.import', $event->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Upload CSV/Excel File</label>
        <input type="file" name="file" id="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Import</button>
</form>
@endsection
