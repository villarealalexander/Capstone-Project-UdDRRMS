@extends('layouts.master-layout')

@section('title', 'Encoder Page')

@section('top-nav-links')
    <form id="searchForm" action="{{ route('encoder.index') }}" method="GET" class="flex mx-2 items-center border border-white rounded-full overflow-hidden shadow-md">
        <input type="text" name="query" id="search" class="w-full py-1 px-4 mx-auto bg-white focus:outline-none text-black font-semibold" placeholder="Search..." value="{{ $searchQuery ?? '' }}" autocomplete="off">
    </form>

    <a href="{{ route('encoder.archives') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fas fa-archive"></i> Archived Students
    </a>

    <a href="{{ route('encoder.upload') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fa-solid fa-upload mr-1"></i>Upload File
    </a>

    <script>
        // Reference link
        //Implemented a search debounce function similar to 
        //this link https://www.freecodecamp.org/news/javascript-debounce-example/
        function debounce(func, delay) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(func, delay);
            }
        }
        function submitForm() {
            const query = document.getElementById('search').value;
            const xhr = new XMLHttpRequest();
            const url = "{{ route('encoder.index') }}?query=" + query;

            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const content = doc.querySelector('tbody').innerHTML;
                    document.querySelector('tbody').innerHTML = content;
                }
            };
            xhr.send();
        }

        const debouncedSubmitForm = debounce(submitForm, 300);

        document.getElementById('search').addEventListener('input', debouncedSubmitForm);
    </script>
@endsection

@section('content')
    <div class="w-full items-center mx-auto mt-4 md:w-[650px] lg:w-full">
        <form id="deleteForm" action="{{ route('encoder.confirm-student-delete') }}" method="GET">
            @csrf
            <div class="flex justify-start items-center mb-2">
                <button type="submit" class="bg-red-500 hover:bg-red-600 py-1 px-2 text-white font-semibold rounded-lg text-lg">Delete Student Folder</button>
                @if (session('success'))
                    <div class="text-green-500 ml-2 mt-2 font-bold text-lg ">{{ session('success') }}</div>
                @endif
            </div>

            <div class="overflow-x-auto" style="height: 580px">
                <table class="min-w-full bg-white text-lg mb-2">
                    <thead class="bg-gray-200 sticky top-0">
                        <tr>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Name</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Batch Year</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Type of Student</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Degree</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Masters/Doctorate</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">
                                <a href="{{ route('encoder.index', ['sort_field' => 'month_uploaded', 'sort_direction' => ($sortParams['field'] === 'month_uploaded' && $sortParams['direction'] === 'asc') ? 'desc' : 'asc']) }}">
                                    Sort Month 
                                    @if ($sortParams['field'] === 'month_uploaded')
                                        @if ($sortParams['direction'] === 'asc')
                                            <i class="fas fa-arrow-up"></i> 
                                        @else
                                            <i class="fas fa-arrow-down"></i> 
                                        @endif
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="text-md">
                        @foreach($students as $student)
                            <tr>
                                <td class="border px-2 py-2 text-center w-1/4">
                                    <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-start cursor-pointer">
                                        <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" class="w-6 h-6 mr-2 folderCheckbox align-middle">
                                        <div class="border-l border-gray-400 pl-2">
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
                                        @switch($student->course)
                                            @case('MIT')
                                                Masters in Information Technology (MIT)
                                                @break
                                                @case('MBA')
                                                Masters in Business Administration (MBA)
                                                @break
                                            @case('MAED')
                                                Master of Arts in Education (MAED)
                                                @break
                                            @case('MED')
                                                Master of Education (MED)
                                                @break
                                            @case('MDB')
                                                Master of Developmental Banking (MDB)
                                                @break
                                            @case('PhD')
                                                Doctor of Philosophy (PhD)
                                                @break
                                            @case('DBA')
                                                Doctor of Business Administration (DBA)
                                                @break
                                            @default
                                                N/A
                                        @endswitch
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="border px-2 py-2 text-center w-1/5">{{ $student->month_uploaded }}</td> <!-- Display Month Uploaded -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection