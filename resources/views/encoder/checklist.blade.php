@extends('layouts.master-layout')

@section('title', 'Checklist Page - ' . $student->name)

@section('top-nav-links')
<a href="{{ route('encoder.index') }}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
     <i class="fa-solid fa-house mr-1"></i>Back to Home
</a>
@endsection

@section('content')
<h1 class="text-center text-xl font-bold mt-4">Checklist for {{ $student->name }}</h1>
<div class="container mx-auto my-10">
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            <div>
                @if (session('success'))
                    <div class="text-green-500 mr-2 mt-1 font-bold text-lg">{{ session('success') }}</div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full bg-white border-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-center">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td class="py-1 px-4 border-b border-gray-300 text-center">
                                    <span class="text-lg font-semibold">{{ $file->description }}</span>
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
