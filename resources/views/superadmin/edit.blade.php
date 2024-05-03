@extends('layouts.master-layout')

@section('title', 'Update User')

@section('top-nav-links')
<a href="{{route('superadmin.index')}}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
@endsection

@section('content')
    <div class="mx-auto max-w-sm my-8 sm:w-3/4 md:w-2/4 lg:w-1/2 xl:w-1/4 ">
        <header class="text-2xl font-semibold bg-white text-gray-500 text-center p-3">
            <h1>Edit User</h1>
        </header>
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <form action="{{ route('superadmin.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                    <div>
                        <div class="mb-4 flex items-center">
                            <label for="name" class="block text-sm font-medium text-gray-600 p-2 ">Name:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                        </div>
                    </div>

                    <div>    
                        <div class="mb-4 flex items-center">
                            <label for="email" class="block text-sm font-medium text-gray-600 p-2">Email:</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                        </div>
                        @if ($errors->has('email'))
                            <p class="ml-16    text-red-500 text-xs italic">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

               
                <div class="mb-4 flex items-center">
                    <label for="role" class="block text-sm font-medium text-gray-600 p-2">Role:</label>
                    <select name="role" id="role" class="mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                        <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                        <option value="viewer" @if($user->role === 'viewer') selected @endif>viewer</option>
                        <option value="encoder" @if($user->role === 'encoder') selected @endif>encoder</option>
                        <option value="superadmin" @if($user->role === 'superadmin') selected @endif>Superadmin</option>
                    </select>
                </div>
                
                    <div>
                        <div class="mb-4 flex items-center">
                            <label for="password" class="block text-sm font-medium text-gray-600 p-2">Password:</label>
                            <input type="password" name="password" id="password" class="mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md">
                        </div>
                    </div>
                    <div>
                        <div class="mb-4 flex items-center">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-600 p-2">Confirm Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-1 w-full border  focus:outline-blue-400 border-black rounded-md">
                        </div>
                    </div>
                
                <div class="mb-4 flex items-center">
                    <label for="superadmin_password" class="block text-sm font-medium text-gray-600 p-2">Superadmin Password:</label>
                    <input type="password" name="superadmin_password" id="superadmin_password" class="mt-1 p-1 w-full border focus:outline-blue-400 border-black rounded-md" required>
                </div>

                <div class="mb-4 flex items-center">
                     <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Update User</button>
                </div>

                <div class="mb-2 flex items-center">
                     <a href="{{route('superadmin.index')}}" class="bg-red-500 text-white text-center px-4 py-2 rounded-md w-full hover:bg-red-600">Cancel</a>
                </div>    
            
                @if(session('success'))
                    <div class="text-green-600 text-center font-medium  mt-4">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
