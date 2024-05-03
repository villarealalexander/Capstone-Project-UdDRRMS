@extends('layouts.master-layout')

@section('title', 'Confirm Delete')

@section('top-nav-links')
<a href="{{ route('encoder.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
@endsection

@section('content')
    <div class="mx-auto max-w-lg mt-10">
        <header class="text-2xl font-semibold mb-4">
            <h1>Confirm Delete</h1>
        </header>

        <main>
            <form action="{{ route('encoder.destroyMultiple') }}" method="POST">
                @csrf
                @foreach($students as $student)
                    <input type="hidden" name="studentsToDelete[]" value="{{ $student->id }}">
                    <p>Are you sure you want to delete the folder "{{ $student->name }}"?</p>
                @endforeach

                <div class="mb-4">
                    <label for="encoder_password" class="block text-sm font-medium text-gray-600">Encoder Password:</label>
                    <input type="password" name="encoder_password" id="encoder_password" class="mt-1 p-2 w-full focus:outline-blue-400 border rounded-md" required>
                    @error('encoder_password')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="w-full text-red-500 font-bold py-2 px-4 border-b text-center">Delete Selected Folders</button>
            </form>
        </main>
    </div>
@endsection