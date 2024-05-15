@extends('layouts.master-layout')

@section('title', 'Superadmin Page')

@section('top-nav-links')
    <button type="button" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2" onclick="openCreateModal('{{ route('superadmin.create') }}')">
        <i class="fas fa-user-plus"></i> Create User
    </button>
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
            <div class="flex justify-start my-2 w-32  ">
                <button type="submit" class="px-2 py-2 bg-red-500 text-sm text-white rounded-lg hover:bg-red-600">Delete Selected</button>
                @if(session('error'))
                    <div class="text-red-500 ml-2 mt-2">{{ session('error') }}</div>
                @endif
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
                                    <button type="button" class="text-white hover:bg-orange-500 bg-orange-300 rounded-md px-2" onclick="openEditModal('{{ route('superadmin.edit', $user->id) }}')">Edit</button>
                                    <button type="button" class="text-white hover:bg-orange-500 bg-orange-300 rounded-md px-2" onclick="openShowModal('{{ route('superadmin.show', $user->id) }}')">Show</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </main>
        </form>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-90"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-200 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            <i class="fa-solid fa-user-pen mr-2"></i>Edit User
                            </h3>
                            <div class="mt-2" id="modal-content">
                                <!-- Form content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Show Modal--->
    <div id="showModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-90"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-200 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            <i class="fa-solid fa-user-pen mr-2"></i>Show User
                            </h3>
                            <div class="mt-2" id="show-modal-content">
                                <!-- Form content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="createModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-90"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-200 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            <i class="fa-solid fa-user-pen mr-2"></i>Create User
                            </h3>
                            <div class="mt-2" id="create-modal-content">
                                <!-- Form content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_users[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        function openEditModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openCreateModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('create-modal-content').innerHTML = data;
                    document.getElementById('createModal').classList.remove('hidden');
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openShowModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('show-modal-content').innerHTML = data;
                    document.getElementById('showModal').classList.remove('hidden');
                });
        }

        function closeShowModal() {
            document.getElementById('showModal').classList.add('hidden');
        }
    </script>
@endsection
