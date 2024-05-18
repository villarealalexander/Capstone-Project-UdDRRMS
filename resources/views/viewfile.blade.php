@extends('layouts.master-layout')

@section('title', 'ViewFile')

@section('top-nav-links')
<a href="{{ route('student.files', ['id' => $student->id]) }}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Student Files
    </a>
@endsection

@section('content')
    <iframe src="{{ $fileUrl }}#toolbar=0" style="width: 100%; height: 95vh;" frameborder="0"></iframe>
@endsection