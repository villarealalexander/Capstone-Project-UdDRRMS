@extends('layouts.master-layout')

@section('title', 'Encoder Page')

@section('top-nav-links')
    <a href="{{route('encoder.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@endsection

@section('content')
    <div class="container mx-auto text-center">
        <h1 class="text-2xl font-bold mb-4 mt-4">Archived Students</h1>
        @if (session('success'))
                    <div class="text-green-500 mr-2 mt-2 font-bold text-lg ">{{ session('success') }}</div>
                @endif
        @if ($archivedStudents->isEmpty())
            <p>No archived students found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class = "border-b-2 border-gray-500 bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Batch Year</th>
                            <th class="px-4 py-2">Deleted At</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($archivedStudents as $student)
                            <tr>
                                <td class="border px-4 py-2">{{ $student->name }}</td>
                                <td class="border px-4 py-2">{{ $student->batchyear }}</td>
                                <td class="border px-4 py-2">{{ $student->deleted_at }}</td>
                                <td class="border px-4 py-2">
                                    <!-- Restore student form -->
                                    <form action="{{ route('encoder.restore', $student->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
