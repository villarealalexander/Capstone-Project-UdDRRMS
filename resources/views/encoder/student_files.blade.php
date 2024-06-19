@extends('layouts.master-layout')

@section('title', 'Student Files - ' . $student->name)

@section('top-nav-links')
    @if (auth()->user()->role === 'encoder')
        <form id="fileUploadForm" action="{{ route('student.addfile', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file[]" id="fileInput" style="display: none;" multiple required>
            <button type="button" id="uploadButton" class="hover:bg-blue-500 px-4 rounded-lg text-white font-semibold text-md mx-2">
                <i class="fa-solid fa-plus mr-1"></i>Add Files
            </button>
        </form>

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
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border-2 border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-center">Description</th>
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-center">File</th>
                                <th class="py-2 px-4 border-b border-gray-300 text-gray-700 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td class="flex justify-center py-4 px-4 border-b border-gray-300">
                                        <div class="flex items-center">
                                            @if (auth()->user()->role === 'encoder')
                                                <form action="{{ route('updatedescription', $file->id) }}" method="POST" class="flex items-center">
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
                                        <div class="flex justify-center items-center">
                                            <img src="{{ asset('images/pdficon.png') }}" alt="PDF Icon" class="w-9 h-9">
                                            <span class="ml-2 text-lg font-semibold">{{ $file->file }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-300 text-center space-y-2">
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

    <!-- JavaScript to trigger file input on button click -->
    <script>
        document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('fileUploadForm').submit();
            }
        });
    </script>
@endsection
