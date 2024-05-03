@extends('layouts.master-layout')

@section('title', 'Create User')

@section('top-nav-links')
    <a href="{{route('superadmin.index')}}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
@endsection

@section('content')
    <div class="mx-auto max-w-sm my-8 sm:w-3/4 md:w-2/4 lg:w-1/2 xl:w-1/4 "">
        <header class="text-2xl font-semibold bg-white text-gray-500 text-center p-3">
            <h1>Create User</h1>
        </header>

        <main>
            <form action="{{ route('superadmin.store') }}" method="POST" class="bg-gray-50 bg-opacity-30 shadow-md rounded-md p-6">
                @csrf

                
                    <div class="mb-4 flex items-center">
                        <label for="name" class="block text-sm font-medium text-gray-600 p-2">Name:</label>
                        <input type="text" name="name" id="name" class="form-input mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                    </div>
                    
                    <div class="mb-4 flex items-center">
                        <label for="email" class="block text-sm font-medium text-gray-600 p-2">Email:</label>
                        <input type="email" name="email" id="email" class="form-input mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                        @if ($errors->has('email'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                

                
                    <div class="mb-4 flex items-center">
                        <label for="role" class="block text-sm font-medium text-gray-600 p-2">Role:</label>
                        <select name="role" id="role" class="form-select mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                            <option value="encoder">Encoder</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                    <div class="mb-4 flex items-center">
                        <label for="password" class="block text-sm font-medium text-gray-600 p-2">Password:</label>
                        <input type="password" name="password" id="password" class="form-input mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                    </div>
                

               
                    <div class="mb-4 flex items-center">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-600 p-2">Confirm Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                    </div>
                    <div class="mb-4 flex items-center">
                        <label for="superadmin_password" class="block text-sm font-medium text-gray-600 p-2">Superadmin Password:</label>
                        <input type="password" name="superadmin_password" id="superadmin_password" class="form-input mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                        @if ($errors->has('superadmin_password'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('superadmin_password') }}</p>
                        @endif
                    </div>
                

                <div class="flex items-center justify-center mt-2">
                    <button type="submit" class="bg-blue-500 w-full hover:bg-blue-600 text-center text-white px-4 py-2 rounded-md focus:outline-none focus:shadow-outline">Create User</button>
                </div>

                <div class="flex items-center justify-center mt-2">
                <a href="{{route('superadmin.index')}}" class="bg-red-500 w-full hover:bg-red-600 text-center text-white px-4 py-2 rounded-md focus:outline-none focus:shadow-outline">Cancel</a>
                </div>

                @if(session('success'))
                    <div class="text-green-600 text-center mt-4">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </main>
    </div>
@endsection
