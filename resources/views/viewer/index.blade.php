@extends('layouts.master-layout')

@section('title', 'Viewer Page')

@section('top-nav-links')
<form action="{{ route('viewer.index') }}" method="GET" class="flex items-center border border-white rounded-full overflow-hidden shadow-md">
    <input type="text" name="query" id="search" class="w-full py-1 px-2 bg-white focus:outline-none text-black font-semibold" placeholder="Search..." value="{{ $searchQuery ?? '' }}" autocomplete="off">
    <button type="submit" class="bg-gray-50 py-1 px-2">
        <i class="fas fa-search text-black"></i>
    </button>
</form>
<a href="{{ route('viewer.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">All Files</a>
<a href="{{ route('viewer.index', ['category' => 'Post Graduate', 'query' => $searchQuery]) }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Post Graduate</a>
<a href="{{ route('viewer.index', ['category' => 'Masteral', 'query' => $searchQuery]) }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Masteral</a>

@endsection

@section('content')
<!-- Flex container with border -->
<div class="w-full items-center mx-auto mt-8  lg:w-3/4"> <!-- Responsive width, adjust as needed -->

    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-lg overflow-hidden text-lg  mb-2 "> <!-- Full width, rounded corners -->
            <!-- Table Head -->
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-2 py-2 border-gray-500 border-b-2 w-1/4">Name</th>
                    <th class="px-2 py-2 border-gray-500 border-b-2">Batch Year</th>
                    <th class="px-2 py-2 border-gray-500 border-b-2">Type of Student</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td class="border px-2 py-2 text-center w-1/4 ">
                        <a href="{{ route('student.files', ['name' => $student->name]) }}" class="flex items-center justify-center cursor-pointer">
                            {{ $student->name }}
                        </a>
                    </td>
                    <td class="border px-2 py-2 text-left w-1/4">
                        <a href="{{ route('student.files', ['name' => $student->name]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                            {{ $student->batchyear }}
                        </a>
                    </td>
                    <td class="border px-2 py-2 text-center w-1/4">
                        <a href="{{ route('student.files', ['name' => $student->name]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                            {{ $student->type_of_student }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Display pagination links -->
    {{ $students->links() }}
</div>

@endsection
