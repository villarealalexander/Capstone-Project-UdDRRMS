@extends('layouts.master-layout')

@section('title', 'Superadmin Page')

@section('top-nav-links')
    <a href="{{ route('superadmin.create') }}" class="hover:bg-blue-100 rounded-lg p-2 text-black font-bold text-md">Create User</a>
    <a href="{{ route('superadmin.activitylogs') }}" class="hover:bg-blue-100 rounded-lg p-2 text-black font-bold text-md">Activity Logs</a>
    <a href="{{ route('superadmin.archives') }}" class="hover:bg-blue-100 rounded-lg p-2 text-black font-bold text-md">Archive Users</a>
@endsection

@section('content')
    <div class="w-full flex flex-col items-center shadow-lg">
        <form id="deleteForm" action="{{ route('superadmin.confirm-delete') }}" method="GET" class="w-full">
            @csrf
            <div class="flex justify-start mb-2 mt-2 w-full">
            <button type="submit" class="px-2 py-2 bg-red-500 text-sm text-white rounded-lg hover:bg-red-600">Delete Selected</button>
                @if(session('error'))
                    <div class="text-red-500 ml-2 mt-2">{{ session('error') }}</div>
                @endif
            </div>

            <main class="w-full overflow-x-auto rounded-xl">
                <table class="min-w-full bg-gray-50 bg-opacity-30 border border-gray-300 shadow-md rounded-md text-xl">
                    <thead class="bg-white sticky top-0">
                        <tr>
                            <th class="py-4 px-2 border-b flex items-center">
                                <input type="checkbox" id="selectAll" class="form-checkbox h-7 w-7 text-blue-500 mr-2">
                            </th>
                            <th class="py-2 border-b text-start">Select All Names</th>
                            <th class="py-2 px-4 border-b text-center">Email</th>
                            <th class="py-2 px-4 border-b text-center">Role</th>
                            <th class="py-2 px-4 border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="py-2 px-2 flex items-center space-x-2">
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="form-checkbox h-7 w-7 text-blue-500">
                                </td>
                                <td class="py-2 text-start border-gray-50">{{ $user->name }}</td>
                                <td class="py-2 px-4 text-center border-gray-50">{{ $user->email }}</td>
                                <td class="py-2 px-4 text-center border-gray-50">{{ $user->role }}</td>
                                <td class="py-2 px-4 text-center">
                                    <a href="{{ route('superadmin.edit', $user->id) }}" class="text-white hover:bg-orange-500 bg-orange-300 rounded-md px-2">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
        </form>
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_users[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
@endsection
