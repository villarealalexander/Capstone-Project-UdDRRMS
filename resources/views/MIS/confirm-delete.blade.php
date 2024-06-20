<div id="confirmDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Confirm User Archive</h2>
        <form id="confirmDeleteForm" action="{{ route('MIS.destroyMultiple') }}" method="POST">
            @csrf
            <div id="usersToDeleteContainer"></div>
            <div class="mb-4">
                <label for="MIS_password" class="block text-sm font-medium text-gray-600">MIS Password:</label>
                <input type="password" name="MIS_password" id="MIS_password" class="mt-1 p-2 w-full focus:outline-blue-400 border rounded-md" required>
            </div>
            <div class="flex justify-start">
                <button type="submit" class="w-auto h-auto text-lg bg-red-500 hover:bg-red-600 text-center text-white px-4 rounded-md focus:outline-none focus:shadow-outline">Confirm Archive</button>
                <button type="button" onclick="closeDeleteModal()" class="ml-4 text-lg px-4 py-2 text-blue-500 underline rounded-md focus:outline-none focus:shadow-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal() {
        const selectedUsers = document.querySelectorAll('input[name="selected_users[]"]:checked');
        if (selectedUsers.length === 0) {
            alert('Please select at least one user to delete.');
            return;
        }

        const usersToDeleteContainer = document.getElementById('usersToDeleteContainer');
        usersToDeleteContainer.innerHTML = '';
        selectedUsers.forEach(user => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'usersToDelete[]';
            input.value = user.value;
            usersToDeleteContainer.appendChild(input);
        });

        document.getElementById('confirmDeleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('confirmDeleteModal').classList.add('hidden');
    }

    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
