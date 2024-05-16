@extends('layouts.master-layout')

@section('title', 'Student Files - ' . $student->name)

@section('top-nav-links')
@if (auth()->user()->role === 'encoder')
<a href="{{route('encoder.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@elseif (auth()->user()->role === 'admin')
<a href="{{route('admin.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@elseif (auth()->user()->role === 'viewer')
<a href="{{route('viewer.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
@endif
@endsection

@section('content')
<div class="container mx-auto my-10">
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            @if (auth()->user()->role === 'encoder')
            <div class="flex justify-end items-center mb-4">
                <form id="fileUploadForm" action="{{ route('student.addfile', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file[]" id="fileInput" style="display: none;" multiple required>
                    <button type="button" id="uploadButton" class="bg-blue-400 hover:bg-blue-600 py-1 px-2 text-white font-semibold rounded-lg text-lg mr-2">Add Files</button>
                </form>
            </div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full bg-white border-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-center">File</th>
                            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                        <tr>
                            <td class="py-1 px-4 border-b border-gray-300 w-1/2">
                                <div class="flex items-center justify-start p-1 border-r-2 mx-auto">
                                    <img src="{{ asset('images/pdficon.png') }}" alt="PDF Icon" class="w-9 h-9">
                                    <span class="text-lg font-semibold ml-1">{{ $file->file }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-300 text-center">
                                <a href="{{ route('viewfile', $file->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">View</a>
                                @if (auth()->user()->role === 'encoder')
                                <form action="{{ route('deletefile', $file->id) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">Delete</button>
                                </form>
                                @endif
                                @if (auth()->user()->role === 'admin')
                                <a href="{{ route('downloadfile', $file->id) }}" class="bg-green-500 hover:bg-green-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">Download</a>
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
