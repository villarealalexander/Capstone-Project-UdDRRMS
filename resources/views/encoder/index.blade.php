@extends('layouts.master-layout')

@section('title', 'Encoder Page')

@section('top-nav-links')
    <form id="searchForm" action="{{ route('encoder.index') }}" method="GET" class="flex mx-2 items-center border border-white rounded-full overflow-hidden shadow-md">
        <input type="text" name="query" id="search" class="w-full py-1 px-4 mx-auto bg-white focus:outline-none text-black font-semibold" placeholder="Search..." value="{{ $searchQuery ?? '' }}" autocomplete="off">
    </form>

    <a href="{{ route('encoder.archives') }}" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fas fa-archive"></i> Archived Students
    </a>

    <button id="openModal" class="hover:bg-blue-600 px-2 text-white py-1 rounded-lg font-semibold text-md mx-2">
        <i class="fa-solid fa-upload mr-1"></i>Upload File
    </button>

    <script>
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
            <button type="button" onclick="openDeleteModal()" class="bg-red-500 hover:bg-red-600 py-1 px-2 text-white font-semibold rounded-lg text-lg">Archive Selected Students</button>

                @if (session('success'))
                    <div class="text-green-500 ml-2 mt-2 font-bold text-lg ">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="text-red-500 ml-2 mt-2 font-bold text-lg ">{{ session('error') }}</div>
                @endif
            </div>

            <div class="overflow-x-auto" style="height: 560px">
                <table class="min-w-full bg-white text-lg mb-2">
                    <thead class="bg-white sticky top-0">
                        <tr>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Name</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Batch Year</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Type of Student</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Degree</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">Masters/Doctorate</th>
                            <th class="px-2 py-2 border-gray-500 border-b-2">
                                <a href="{{ route('encoder.index', ['sort_field' => 'month_uploaded', 'sort_direction' => ($sortParams['field'] === 'month_uploaded' && $sortParams['direction'] === 'asc') ? 'desc' : 'asc']) }}">
                                <i class="fa-solid fa-sort mr-2"></i>Sort Month 
                                    @if ($sortParams['field'] === 'month_uploaded')
                                        @if ($sortParams['direction'] === 'asc')
                                            <i class="fas fa-arrow-up"></i> 
                                        @else
                                            <i class="fas fa-arrow-down"></i> 
                                        @endif
                                    @endif
                                </a>
                            </th>

                            <th class="px-2 py-2 border-gray-500 border-b-2">Checklist</th>
                        </tr>
                    </thead>

                    <tbody class="text-md bg-gray-50">
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
    
                                <td class="border px-2 py-2 text-left w-auto">
                                    <a href="{{ route('student.files', ['id' => $student->id]) }}" class="flex items-center justify-center cursor-pointer hover:underline">
                                        {{ $student->batchyear }}
                                    </a>
                                </td>

                                <td class="border px-2 py-2 text-center w-auto">
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

                                <td class="border px-2 py-2 text-center w-1/5  ">{{ $student->month_uploaded }}</td>
                                <td class="border px-2 py-2 text-center w-1/2">
                                <a href="#"  class="text-white hover:bg-orange-500 bg-orange-300 rounded-md px-2"
                                    onclick="openChecklistModal('{{ $student->name }}', '{{ route('encoder.checklist', ['student_id' => $student->id]) }}')">
                                        Checklist
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <div id="checklistModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 relative">
        <div class="mb-4">
            <h2 class="text-xl font-bold" id="checklistModalTitle"></h2>
        </div>
        <div id="checklistModalContent"></div>
        <div class="flex justify-end mt-4">
            <button class="text-gray-600 hover:text-gray-800 focus:outline-none" onclick="closeChecklistModal()">
                Close <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>


    @include('encoder.upload')
    @include('encoder.confirm-student-delete')

    
@endsection

<script>
    function openChecklistModal(studentName, checklistUrl) {
        document.getElementById('checklistModalTitle').textContent = 'Checklist for ' + studentName;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', checklistUrl, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('checklistModalContent').innerHTML = xhr.responseText;
                document.getElementById('checklistModal').classList.remove('hidden');
            }
        };
        xhr.send();
    }

    function closeChecklistModal() {
        document.getElementById('checklistModal').classList.add('hidden');
        document.getElementById('checklistModalContent').innerHTML = '';
    }
</script>

