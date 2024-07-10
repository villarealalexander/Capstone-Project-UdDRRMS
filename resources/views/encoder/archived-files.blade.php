@extends('layouts.master-layout')

@section('title', 'Archived Files - ' . $student->name)

@section('top-nav-links')

<a href="{{ route('student.files', ['id' => $student->id]) }}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-sm mx-2">
<i class="fa-solid fa-rotate-left mr-1"></i>Back to {{ $student->name }}'s Files
    </a>
@endsection

@section('content')
<div class="container mx-auto my-10">
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            <h1 class="text-center text-2xl font-bold mb-4 ">Archived Files - {{ $student->name }}</h1>
            @if (session('success'))
                <div class="text-green-500 mr-2 mt-2 font-bold text-lg">{{ session('success') }}</div>
            @endif
            @if ($archivedFiles->isEmpty())
                <p class="text-center">No archived files found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-center">File</th>
                                <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedFiles as $file)
                            <tr>
                                <td class="py-1 px-4 border-b border-gray-300 w-1/2">
                                    <div class="flex items-center justify-start p-1 border-r-2 mx-auto">
                                        <img src="{{ asset('images/pdficon.png') }}" alt="PDF Icon" class="w-9 h-9">
                                        <span class="text-lg font-semibold ml-1">{{ $file->file }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border-b border-gray-300 text-center">
                                    <form action="{{ route('restorefile', $file->id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">
                                        <i class="fa-solid fa-trash-can-arrow-up mr-1"></i>Restore</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
