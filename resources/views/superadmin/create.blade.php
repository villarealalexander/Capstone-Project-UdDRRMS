@extends('layouts.master-layout')

@section('title', 'Create User')

@section('top-nav-links')
    <a href="{{route('superadmin.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@endsection

@section('page-title')
<i class="fa-solid fa-user-plus mr-2"></i>Create User
@endsection

@section('content')
<div class="mx-auto max-w-sm my-8 sm:w-3/4 md:w-2/4 lg:w-1/2 xl:w-1/4 "">
<div class = "w-full bg-gray-50 p-6 rounded-lg">
    <form action="{{ route('superadmin.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" id="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                <option value="" disabled selected>Select User Role</option>
                <option value="admin">Admin</option>
                <option value="viewer">Viewer</option>
                <option value="encoder">Encoder</option>
                <option value="superadmin">Superadmin</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            @if ($errors->has('email'))
                <p class="text-red-500 text-xs italic mt-2">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="superadmin_password" class="block text-sm font-medium text-gray-700">Superadmin Password</label>
            <input type="password" name="superadmin_password" id="superadmin_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            @if ($errors->has('superadmin_password'))
                <p class="text-red-500 text-xs italic mt-2">{{ $errors->first('superadmin_password') }}</p>
            @endif
        </div>

        <div class="flex justify-start items-center">
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Create User</button>
            <a href = "{{route('superadmin.index')}}" class="mt-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</a>
        </div>

        @if(session('success'))
            <div class="text-green-600 text-center font-medium mt-4">
                {{ session('success') }}
            </div>
        @endif
    </form>
    </div>
</div>
@endsection