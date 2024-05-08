@extends('layouts.master-layout')

@section('title', 'Archived Users')

@section('top-nav-links')
    <a href="{{ route('superadmin.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
    <a href="{{ route('superadmin.create') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Create User</a>
    <a href="{{ route('superadmin.activitylogs') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Activity Logs</a>
@endsection

@section('content')
    <div class="mx-auto flex flex-col items-center sm:w-11/12 md:w-4/5 lg:w-3/4 xl:w-5/6">
        <main class="w-full overflow-x-auto" style="max-height: 500px">
        <div class="flex justify-end mb-2 mt-2">
                @if (session('success'))
                    <div class="text-green-500 mr-2 mt-2 font-bold text-lg ">{{ session('success') }}</div>
                @endif
            <a href="{{ route('superadmin.index') }}" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Back to User's List</a>
        </div>
            <table class="min-w-full bg-gray-50 bg-opacity-30 border border-gray-300 shadow-md rounded-md text-xl">
                <thead class="bg-white sticky top-0">
                    <tr>
                        <th class="text-gray-500 py-2 px-4 border-b">Name</th>
                        <th class="text-gray-500 py-2 px-4 border-b">Email</th>
                        <th class="text-gray-500 py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($archivedUsers as $user)
                        <tr class="border-b-2 border-gray-400 text-center">
                            <td class="py-2 px-12 border-b">{{ $user->name }}</td>
                            <td class="text-center py-2 px-12 border-b">{{ $user->email }}</td>
                            <td class="py-2 px-12 border-b text-center">
                                <form action="{{ route('superadmin.restore', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Restore</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
@endsection
