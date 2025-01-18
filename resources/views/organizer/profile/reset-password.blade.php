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

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Reset Password</h3>


                <!--Error handler-->

        <!-- Display error messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            @if (session('status'))
                <div>{{ session('status') }}</div>
            @endif

            @error('email')
                <div>{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-danger w-100">Reset</button>
        </form>

    </div>
</div>

@endsection

@section('footer')

@endsection
