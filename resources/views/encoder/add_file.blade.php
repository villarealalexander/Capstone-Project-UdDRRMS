<!-- add_file.blade.php -->
@extends('layouts.master-layout')

@section('title', 'Add File to Student')

@section('content')
<div class="container mx-auto my-10">
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            <form action="{{ route('encoder.addFile', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Select File(s) to Upload:</label>
                    <input type="file" name="file[]" id="file" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" multiple required>
                    @error('file.*')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Upload File(s)</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
