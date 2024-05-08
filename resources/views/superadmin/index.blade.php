@extends('layouts.master-layout')

@section('title', 'Superadmin Page')

@section('top-nav-links')
    <a href="{{ route('superadmin.create') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Create User</a>
    <a href="{{ route('superadmin.activitylogs') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Activity Logs</a>
@endsection

@section('content')
    <div class="mx-auto flex flex-col items-center sm:w-11/12 md:w-4/5 lg:w-3/4 xl:w-5/6">
        <form id="deleteForm" action="{{ route('superadmin.confirm-delete') }}" method="GET">
            @csrf

            <div class="flex justify-end mb-2 mt-2">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete Selected</button>

                @if(session('error'))
                    <div class="text-red-500 ml-2 mt-2">{{ session('error') }}</div>
                @endif
            </div>

            <main class="w-full overflow-x-auto" style="max-height: 500px">
                <table class="min-w-full bg-gray-50 bg-opacity-30 border border-gray-300 shadow-md rounded-md text-xl">
                    <thead class="bg-white sticky top-0">
                        <tr>
                            <th class="text-gray-500 py-2 px-4 border-b">Name</th>
                            <th class="text-gray-500 py-2 px-4 border-b">Email</th>
                            <th class="text-gray-500 py-2 px-4 border-b">Role</th>
                            <th class="text-gray-500 py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b-2 border-gray-400">
                                <td class="py-2 px-12 border-b flex items-center space-x-2">
                                    <!-- Checkbox for selecting user -->
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="form-checkbox h-5 w-5 mr-2 text-blue-500">
                                    <span>{{ $user->name }}</span>
                                </td>
                                <td class="text-center py-2 px-12 border-b">{{ $user->email }}</td>
                                <td class="text-center py-2 px-12 border-b">{{ $user->role }}</td>
                                <td class="py-2 px-12 border-b text-center">
                                    <a href="{{ route('superadmin.show', $user->id) }}" class="text-blue-500 hover:underline">Show</a>
                                    <a href="{{ route('superadmin.edit', $user->id) }}" class="text-yellow-500 hover:underline ml-2">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
        </form>
    </div>
@endsection
