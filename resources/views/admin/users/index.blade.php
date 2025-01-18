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

<h1>Manage Users</h1>
<a href="{{ route('admin.user.create') }}" class="btn btn-primary">Add New User</a>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
