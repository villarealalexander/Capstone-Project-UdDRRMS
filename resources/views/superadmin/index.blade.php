@extends('layouts.master-layout')

@section('title', 'Superadmin Page')

@section('top-nav-links')
    <a href="{{ route('superadmin.create') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fas fa-user-plus"></i> Create User
    </a>
    <a href="{{ route('superadmin.activitylogs') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fas fa-clipboard-list"></i> Activity Logs
    </a>
    <a href="{{ route('superadmin.archives') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fas fa-archive"></i> Archive Users
    </a>
@endsection

@section('content')
    <div class="w-full flex flex-col items-center shadow-lg">
        <form id="deleteForm" action="{{ route('superadmin.confirm-delete') }}" method="GET" class="w-full">
            @csrf
            <div class="flex justify-start my-2 w-32">
                <button type="submit" class="px-2 py-2 bg-red-500 text-sm text-white rounded-lg hover:bg-red-600">Delete Selected</button>
            </div>

            <main class="w-full overflow-x-auto rounded-xl">
                <table class="min-w-full bg-gray-50 bg-opacity-30 border border-gray-300 shadow-md rounded-md text-xl">
                    <thead class="bg-gray-50 sticky top-0 shadow-md">
                        <tr>
                            <th class="py-4 px-2 border-b flex items-center">
                                <input type="checkbox" id="selectAll" class="form-checkbox h-7 w-7 text-blue-500 mr-2">
                            </th>
                            <th class="border-b text-start">Select All Users</th>
                            <th class="px-4 border-b text-center">Email</th>
                            <th class="px-4 border-b text-center">Role</th>
                            <th class="px-4 border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="py-2 px-2 flex items-center space-x-4">
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="form-checkbox h-7 w-7 text-blue-500">
                                </td>
                                <td class="py-2 text-start border-gray-50">{{ $user->name }}</td>
                                <td class="py-2 px-4 text-center border-gray-50">{{ $user->email }}</td>
                                <td class="py-2 px-4 text-center border-gray-50">{{ $user->role }}</td>
                                <td class="py-2 px-4 text-center">
                                <a href="{{ route('superadmin.edit', $user->id) }}" class="text-white hover:bg-blue-800 bg-blue-500 rounded-md px-2">Edit</a>
                                <a href="{{ route('superadmin.show', $user->id) }}" class="text-white hover:bg-orange-500 bg-orange-300 rounded-md px-2">Show</a>

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
