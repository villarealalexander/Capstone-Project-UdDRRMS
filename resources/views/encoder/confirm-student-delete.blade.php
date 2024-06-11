<div id="confirmDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Confirm Student Deletion</h2>
        <form id="confirmDeleteForm" action="{{ route('encoder.destroyMultiple') }}" method="POST">
            @csrf
            <div id="studentsToDeleteContainer"></div>
            <div class="mb-4">
                <label for="encoder_password" class="block text-sm font-medium text-gray-600">Encoder Password:</label>
                <input type="password" name="encoder_password" id="encoder_password" class="mt-1 p-2 w-full focus:outline-blue-400 border rounded-md" required>
            </div>
            <div class="flex justify-start">
                <button type="submit" class="w-auto h-auto text-lg bg-red-500 hover:bg-red-600 text-center text-white px-4 rounded-md focus:outline-none focus:shadow-outline">Confirm Delete</button>
                <button type="button" onclick="closeDeleteModal()" class="ml-4 text-lg px-4 py-2 text-blue-500 underline rounded-md focus:outline-none focus:shadow-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal() {
        const selectedStudents = document.querySelectorAll('input[name="selected_students[]"]:checked');
        if (selectedStudents.length === 0) {
            alert('Please select at least one student to delete.');
            return;
        }

        const studentsToDeleteContainer = document.getElementById('studentsToDeleteContainer');
        studentsToDeleteContainer.innerHTML = '';
        selectedStudents.forEach(student => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'studentsToDelete[]';
            input.value = student.value;
            studentsToDeleteContainer.appendChild(input);
        });

        document.getElementById('confirmDeleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('confirmDeleteModal').classList.add('hidden');
    }
</script>
