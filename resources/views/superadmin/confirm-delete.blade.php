@extends('layouts.master-layout')

@section('title', 'Confirm User Deletion')

@section('top-nav-links')
    <a href="{{route('superadmin.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-lg mt-10">
        <header class="text-2xl font-semibold mb-4">
            <h1>Confirm User Deletion</h1>
        </header>

        <main>
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <form action="{{ route('superadmin.destroyMultiple') }}" method="POST">
                    @csrf
                    @foreach($userIds as $userId)
                        <input type="hidden" name="usersToDelete[]" value="{{ $userId }}">
                    @endforeach

                    <div class="mb-4">
                        <label for="superadmin_password" class="block text-sm font-medium text-gray-600">Superadmin Password:</label>
                        <input type="password" name="superadmin_password" id="superadmin_password" class="mt-1 p-2 w-full focus:outline-blue-400 border rounded-md" required>
                        @if(session('error'))
                             <div class="text-red-500 ml-2 mt-2">{{ session('error') }}</div>
                        @endif
                        
                    </div>

                    <div class="flex justify-start">
                        <button type="submit" class="w-auto h-auto text-lg bg-red-500 hover:bg-red-600 text-center text-white px-4 rounded-md focus:outline-none focus:shadow-outline">Confirm Delete</button>
                        <a href="{{ route('superadmin.index') }}" class="ml-4 text-lg px-4 py-2 text-blue-500 underline rounded-md focus:outline-none focus:shadow-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
