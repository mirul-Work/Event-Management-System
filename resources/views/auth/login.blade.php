@extends('layouts.half-navigation')

@section('title', 'Event Management System')
@section('child-title', 'Event Management System')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-800 to-pink-800">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h3>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Info Message -->
        @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                {{ session('info') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('errors'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('errors') }}
            </div>
        @endif

        <form action="{{ route('auth.verify') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Email input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" id="email" name="email" class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring focus:ring-purple-400" placeholder="Enter your email" required>
            </div>

            <!-- Password input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring focus:ring-purple-400" placeholder="Enter your password" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition duration-300">Login</button>
        </form>

        <!-- Links -->
        <div class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:underline">Forgot Password?</a>
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('auth.register') }}" class="text-sm text-purple-600 hover:underline">Create an account?</a>
        </div>
    </div>
</div>
@endsection

@section('footer')
@endsection
