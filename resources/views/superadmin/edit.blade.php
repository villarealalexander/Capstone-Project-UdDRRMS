<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3">
        <h2 class="text-2xl font-semibold mb-4">Edit User</h2>
        <form id="editUserForm" action="#" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="editRole" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    <option value="admin">Admin</option>
                    <option value="viewer">Viewer</option>
                    <option value="encoder">Encoder</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="editName" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="editEmail" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                <p id="editEmailError" class="text-red-500 text-xs italic mt-2 hidden"></p>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="editPassword" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="editPasswordConfirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="superadmin_password" class="block text-sm font-medium text-gray-700">Superadmin Password</label>
                <input type="password" name="superadmin_password" id="editSuperadminPassword" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>

            <div class="flex justify-start items-center">
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Update User</button>
                <button type="button" id="closeEditUserModal" class="ml-3 bg-red-500 text-white py-2 px-4 rounded-md shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditUserModal(user) {
        const form = document.getElementById('editUserForm');
        form.action = `{{ url('superadmin/update') }}/${user.id}`;
        document.getElementById('editRole').value = user.role;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    document.getElementById('closeEditUserModal').addEventListener('click', function() {
        document.getElementById('editUserModal').classList.add('hidden');
    });
</script>