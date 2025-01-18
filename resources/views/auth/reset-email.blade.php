@extends('layouts.half-navigation')

@section('title', 'Event Management System')
@section('child-title', 'Event Management System')


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

        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif


        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="w-full bg-purple-800 text-white py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                Reset
            </button>
        </form>

    </div>
</div>

@endsection

@section('footer')

@endsection
