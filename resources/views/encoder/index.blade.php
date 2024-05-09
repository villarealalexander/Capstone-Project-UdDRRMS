@extends('layouts.master-layout')

@section('title', 'Encoder Page')

@section('top-nav-links')
    <form action="{{ route('encoder.index') }}" method="GET" class="flex items-center border border-white rounded-full overflow-hidden shadow-md">
        <input type="text" name="query" id="search" class="w-full py-1 px-2 bg-white focus:outline-none text-black font-semibold" placeholder="Search..." value="{{ $searchQuery ?? '' }}" autocomplete="off">
        <button type="submit" class="bg-gray-50 py-1 px-2">
            <i class="fas fa-search text-black"></i>
        </button>
    </form>

    <a class="nav-link hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm" href="{{ route('encoder.archive') }}">Archives</a>
@endsection

@section('content')
    <div class="w-full items-center mx-auto mt-4 md:w-[650px] lg:w-3/4">
        <div class="flex justify-end items-center mb-4">
            <a href="{{ route('encoder.upload') }}" class="bg-green-400 hover:bg-green-600 py-1 px-2 text-white font-semibold rounded-lg text-lg mr-2">Upload File</a>
            <form id="deleteForm" action="{{ route('encoder.confirm-delete') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 py-1 px-2 text-white font-semibold rounded-lg text-lg">Delete Student Folder</button>
        </div>
        
        <div class="overflow-x-auto" style ="max-height:500px">
            <table class="w-full bg-white rounded-lg text-lg mb-2 ">
                <thead class="bg-gray-200 sticky top-0">
                    <tr>
                        <th class="px-2 py-2 border-gray-500 border-b-2 ">Name</th>
                        <th class="px-2 py-2 border-gray-500 border-b-2">Batch Year</th>
                        <th class="px-2 py-2 border-gray-500 border-b-2">Type of Student</th>
                        <th class="px-2 py-2 border-gray-500 border-b-2">DEGREE</th>
                        <th class="px-2 py-2 border-gray-500 border-b-2">Masters/Doctorate</th>
                    </tr>
                </thead>

                <tbody class= "text-md">
                    @foreach($students as $student)
                        <tr>
                            <td class="border px-2 py-2 text-center w-1/4 ">
                                <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-start cursor-pointer">
                                    <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" class="w-6 h-6 mr-2 folderCheckbox align-top">
                                    <div class="border-l border-gray-400 pl-20">
                                        <span class="text-black hover:underline">{{ $student->name }}</span>
                                    </div>
                                </a>
                            </td>

                            <td class="border px-2 py-2 text-left w-1/4">
                                <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                                    {{ $student->batchyear }}
                                </a>
                            </td>

                            <td class="border px-2 py-2 text-center w-1/4">
                                <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                                    {{ $student->type_of_student }}
                                </a>
                            </td>

                            <td class="border px-2 py-2 text-center w-1/4">
                                <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                                    @if ($student->major)
                                    {{ $student->course }} major in {{ $student->major }}
                                    @else
                                    {{ $student->course }} 
                                    @endif
                                </a>
                            </td>

                            <td class="border px-2 py-2 text-center w-1/5">
                                @if ($student->type_of_student === 'Post Graduate')
                                    @if ($student->course === 'MIT')
                                        Masters in Information Technology (MIT)
                                    @elseif ($student->course === 'MBA')
                                        Masters in Business Administration (MBA)
                                    @elseif ($student->course === 'MAED')
                                        Master of Arts in Education (MAED)
                                    @elseif ($student->course === 'MED')
                                        Master of Education (MED)
                                    @elseif ($student->course === 'MDB')
                                        Master of Developmental Banking (MDB)
                                    @elseif ($student->course === 'PhD')
                                        Doctor of Philosophy (PhD)
                                    @elseif ($student->course === 'DBA')
                                        Doctor of Business Administration (DBA)
                                    @else
                                        N/A
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </form>
        {{ $students->links() }}
    </div>
@endsection
