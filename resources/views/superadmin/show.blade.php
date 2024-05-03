@extends('layouts.master-layout')

@section('title', 'Show User')

@section('content')
    <div class="mx-auto max-w-lg mt-10">
        <header class="text-2xl bg-gray-50 text-gray-500 text-center p-4 font-semibold">
            <h1>Show User</h1>
        </header>

        <main>
            <div class="bg-gray-50 bg-opacity-30 shadow-md rounded-md p-6">
                <h2 class="text-2xl font-semibold mb-4">{{ $user->name }}</h2>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ $user->role }}</p>
            </div>

            <div class="mt-4">
                <a href="{{ route('superadmin.index') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">Back to User List</a>
            </div>
        </main>
    </div>
@endsection