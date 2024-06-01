<div id="showUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3">
        <h2 class="text-2xl font-semibold mb-4">User Details</h2>
        <div>
            <p><strong>Name:</strong> <span id="userName"></span></p>
            <p><strong>Email:</strong> <span id="userEmail"></span></p>
            <p><strong>Role:</strong> <span id="userRole"></span></p>
            <p><strong>Created At:</strong> <span id="userCreatedAt"></span></p>
        </div>
        <div class="flex justify-end items-center mt-4">
            <button type="button" id="closeShowUserModal" class="bg-red-500 text-white py-2 px-4 rounded-md shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Close</button>
        </div>
    </div>
</div>

<script>
    function showUserModal(user) {
        document.getElementById('userName').innerText = user.name;
        document.getElementById('userEmail').innerText = user.email;
        document.getElementById('userRole').innerText = user.role;
        document.getElementById('userCreatedAt').innerText = new Date(user.created_at).toLocaleString();
        document.getElementById('showUserModal').classList.remove('hidden');
    }

    document.getElementById('closeShowUserModal').addEventListener('click', function() {
        document.getElementById('showUserModal').classList.add('hidden');
    });
</script>