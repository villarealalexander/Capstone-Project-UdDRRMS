<div id="createUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3">
            <h2 class="text-2xl font-semibold mb-4">Create User</h2>
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
                    <button type="button" id="closeCreateUserModal" class="ml-3 bg-red-500 text-white py-2 px-4 rounded-md shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('openCreateUserModal').addEventListener('click', function() {
            document.getElementById('createUserModal').classList.remove('hidden');
        });

        document.getElementById('closeCreateUserModal').addEventListener('click', function() {
            document.getElementById('createUserModal').classList.add('hidden');
        });
    </script>