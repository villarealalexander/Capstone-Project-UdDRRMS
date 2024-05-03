@extends('layouts.master-layout')

@section('title', 'Student Files - ' . $name)

@section('top-nav-links')
    @php
        $userRole = auth()->user()->role;
    @endphp

    @if ($userRole === 'encoder')
        <a href="{{ route('encoder.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
        <a href="{{ route('encoder.upload') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Upload File</a>
        @elseif ($userRole === 'viewer')
        <a href="{{ route('viewer.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
    @elseif ($userRole === 'admin')
        <a href="{{ route('admin.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
    @endif
@endsection

@section('content')
<div class="container mx-auto my-10">
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2 ">
            <div class="overflow-x-auto">
                <table class="w-full bg-white  border-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700  border-gray-300 text-center">File</th>
                            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700  border-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                        <tr>
                        <td class="py-1 px-4 border-b border-gray-300 w-1/2">
                            <div class="flex items-center justify-start p-1 border-r-2 mx-auto">
                                <img src="{{ asset('images/pdficon.png') }}" alt="PDF Icon" class="w-9 h-9">
                                <span class="text-lg font-semibold ml-1 ">{{ $file->file }}</span>
                            </div>
                        </td>

                            <td class="py-2 px-4 border-b border-gray-300 text-center">
                                <a href="{{ route('viewfile', $file->id) }}" class="bg-blue-500 hover:bg-blue-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">View</a>
                                
                                @if ($userRole === 'encoder')
                                    <form action="{{ route('deletefile', $file->id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 py-1 px-2 text-white font-semibold rounded-lg text-sm">Delete</button>
                                    </form>
                                @endif

                                @if ($userRole === 'admin')
                                    <a href="{{ route('downloadfile', $file->id) }}" class="bg-green-500 py-1 px-2 text-sm rounded-lg text-white font-semibold hover:underline ml-2">Download</a>
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
@endsection
