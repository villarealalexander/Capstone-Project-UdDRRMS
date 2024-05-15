<div class="mx-auto max-w-lg mt-10">
    <header class="text-2xl bg-white text-black text-center p-4 font-semibold shadow-md">
        <h1>Show User</h1>
    </header>

    <main>
        <div class="bg-gray-50 bg-opacity-30 rounded-md p-6">
            <h2 class="text-2xl font-semibold mb-4">{{ $user->name }}</h2>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
            <p><strong>Created At:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="mt-4">
            <button type="button" class="inline-flex justify-start rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeShowModal()">Back</button>
        </div>
    </main>
</div>
