@extends('layouts.master-layout')

@section('title', 'Student Files - ' . $student->name)

@section('top-nav-links')
    @if (auth()->user()->role === 'encoder')
        <button type="button" id="openModalButton" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
            <i class="fa-solid fa-plus mr-1"></i>Add Files
        </button>

        <a href="{{ route('encoder.archived-files', $student->id) }}" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
            <i class="fa-solid fa-trash mr-1"></i>Archived Files
        </a>

        <a href="{{ route('encoder.index') }}" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
            <i class="fa-solid fa-house mr-1"></i>Back to Home
        </a>
    @elseif (auth()->user()->role === 'admin')
        <a href="{{ route('admin.index') }}" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
            <i class="fa-solid fa-house mr-1"></i>Back to Home
        </a>
    @elseif (auth()->user()->role === 'viewer')
        <a href="{{ route('viewer.index') }}" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
            <i class="fa-solid fa-house mr-1"></i>Back to Home
        </a>
    @endif
@endsection

@section('content')
    <h1 class="text-center text-2xl font-bold mt-4">Student - {{ $student->name }}</h1>
    <div class="container mx-auto my-10">
        <div class="flex justify-center">
            <div class="w-full">
                @if (session('success'))
                    <div class="text-green-500 mr-2 mt-1 font-bold text-lg">{{ session('success') }}</div>
                @endif
                <div class="overflow-x-auto" style="max-height: 530px">
                    <table class="w-full bg-gray-200 border-2 border-gray-300 rounded-lg shadow-md">
                        <thead class="sticky top-0">
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-left">Description</th>
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-left">File</th>
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td class="flex justify-left py-4 px-4 border-b border-gray-300">
                                        <div class="flex items-left">
                                            @if (auth()->user()->role === 'encoder')
                                                <form action="{{ route('updatedescription', $file->id) }}" method="POST" class="flex items-left">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="description" value="{{ $file->description }}" class="w-full border-gray-300 border rounded py-1 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                        onchange="this.form.submit()">
                                                    @if ($file->description !== $file->getOriginal('description'))
                                                        <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded">
                                                            Save
                                                        </button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-lg font-semibold">{{ $file->description }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-300">
                                        <div class="flex justify-left items-left">
                                            <img src="{{ asset('images/pdficon.png') }}" alt="PDF Icon" class="w-9 h-9">
                                            <span class="ml-2 text-lg font-semibold">{{ $file->file }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-300 text-left space-y-2">
                                        <a href="{{ route('viewfile', $file->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-2 rounded-lg text-sm mr-2">
                                            <i class="fa-solid fa-file-pdf mr-1"></i>View
                                        </a>
                                        @if (auth()->user()->role === 'encoder')
                                            <form action="{{ route('deletefile', $file->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-2 rounded-lg text-sm">
                                                    <i class="fa-solid fa-trash mr-1"></i>Move to Archive
                                                </button>
                                            </form>
                                        @elseif (auth()->user()->role === 'admin')
                                            <a href="{{ route('downloadfile', $file->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-2 rounded-lg text-sm">
                                                <i class="fa-solid fa-download mr-1"></i>Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="uploadModal" class="fixed inset-0 flex items-left justify-left bg-gray-800 bg-opacity-75 hidden backdrop-blur-sm">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8 sm:max-w-md md:max-w-sm xl:max-w-md">
            <div class="flex justify-between items-center bg-white p-1 rounded-t-lg">
                <h1 class="text-xl sm:text-3xl text-gray-600 font-bold mt-2 sm:mt-0 ml-4">Upload File</h1>
                <button id="closeModalButton" class="text-gray-600 hover:text-gray-900 text-lg">&times;</button>
            </div>

            <form id="fileUploadForm" action="{{ route('student.addfile', $student->id) }}" method="POST" enctype="multipart/form-data" class="px-5 py-4">
                @csrf
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm sm:text-base">Description:</label>
                    <input type="text" name="description" id="description" placeholder="Enter file description" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full" required>
                </div>

                <div class="mb-4">
                    <label for="file" class="block text-gray-700 text-sm sm:text-base">Choose File:</label>
                    <input type="file" name="file[]" id="file" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 hover:bg-blue-600">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to handle modal operations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalButton = document.getElementById('openModalButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const uploadModal = document.getElementById('uploadModal');

            openModalButton.addEventListener('click', () => {
                uploadModal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', () => {
                uploadModal.classList.add('hidden');
            });
        });
    </script>
@endsection
