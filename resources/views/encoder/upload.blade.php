@extends('layouts.master-layout')

@section('title', 'Upload File')

@section('top-nav-links')
    <a href="{{ route('encoder.index') }}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
@endsection

@section('content')
<div class="flex justify-center items-center my-5 mx-auto px-4">
    <form action="{{ url('uploadfile') }}" method="post" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg w-full max-w-4xl p-8 sm:max-w-md md:max-w-sm lg:max-w-lg xl:max-w-md">
        @csrf

        <div class="flex justify-center items-center bg-white p-1 rounded-t-lg">
            <img class="w-12 h-12 sm:w-20 sm:h-20" src="{{ asset('images/folder.png') }}" alt="Folder Icon">
            <h1 class="text-xl sm:text-3xl text-gray-600 font-bold mt-2 sm:mt-0 ml-4">Upload File</h1>
        </div>
        
        <div class="px-5 py-4">
            <div class="mb-4">
                <label for="Name" class="block text-gray-700 text-sm sm:text-base">Student Name:</label>
                <input type="text" name="Name" id="Name" placeholder="Enter student name" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full">
                @error('Name')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="BatchYear" class="block text-gray-700 text-sm sm:text-base">Batch Year:</label>
                <input type="text" name="BatchYear" id="BatchYear" placeholder="Enter batch year" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full">
                @error('BatchYear')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="type_of_student" class="block text-gray-700 text-sm sm:text-base">Type of Student:</label>
                <select name="type_of_student" id="type_of_student" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Type of Student</option>
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Post Graduate">Post Graduate</option>
                </select>
                @error('type_of_student')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div id="undergradCoursesDropdown" class="mb-4 hidden">
                <label for="undergradCourses" class="block text-gray-700 text-sm sm:text-base">Degree:</label>
                <select name="undergradCourses" id="undergradCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Degree</option>
                    <option value="BS IN INFORMATION TECHNOLOGY">BS IN INFORMATION TECHNOLOGY</option>
                    <option value="BS IN COMPUTER SCIENCE">BS IN COMPUTER SCIENCE</option>
                    <option value="BS IN ACCOUNTANCY">BS IN ACCOUNTANCY</option>
                    <option value="BS IN BUSINESS ADMINISTRATION">BS IN BUSINESS ADMINISTRATION</option>
                    <option value="BS IN ELECTRONICS ENGINEERING">BS IN ELECTRONICS ENGINEERING</option>
                    <option value="BS IN CIVIL ENGINEERING">BS IN CIVIL ENGINEERING</option>
                    <option value="BS IN ELECTRICAL ENGINEERING">BS IN ELECTRICAL ENGINEERING</option>
                    <option value="BS IN COMPUTER ENGINEERING">BS IN COMPUTER ENGINEERING</option>
                    <option value="BS IN NURSING">BS IN NURSING</option>
                    <option value="BA IN COMMUNICATION">BA IN COMMUNICATION</option>
                    <option value="BS IN PSYCHOLOGY">BS IN PSYCHOLOGY</option>
                    <option value="BS IN HOSPITALITY MANAGEMENT">BS IN HOSPITALITY MANAGEMENT</option>
                    <option value="BS IN TOURISM MANAGEMENT">BS IN TOURISM MANAGEMENT</option>
                    <option value="BS IN CRIMINOLOGY">BS IN CRIMINOLOGY</option>
                    <option value="BACHELOR OF SECONDARY EDUCATION">BACHELOR OF SECONDARY EDUCATION</option>
                    <option value="BACHELOR OF ELEMENTARY EDUCATION">BACHELOR OF ELEMENTARY EDUCATION</option>
                    <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION">BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                    <option value="BACHELOR OF SPECIAL NEEDS EDUCATION">BACHELOR OF SPECIAL NEEDS EDUCATION</option>
                </select>
                @error('undergradCourses')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div id="postGradDegreeDropdown" class="mb-4 hidden">
                <label for="postGradDegrees" class="block text-gray-700 text-sm sm:text-base">Degree Level:</label>
                <select name="postGradDegrees" id="postGradDegrees" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Degree Type</option>
                    <option value="Masters">Masters</option>
                    <option value="Doctorate">Doctorate</option>
                </select>
                @error('postGradDegrees')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div id="mastersCoursesDropdown" class="mb-4 hidden">
                <label for="mastersCourses" class="block text-gray-700 text-sm sm:text-base">Masters Courses:</label>
                <select name="mastersCourses" id="mastersCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Masters Course</option>
                    <option value="MBA">MBA</option>
                    <option value="MAED">MAED</option>
                    <option value="MIT">MIT</option>
                    <option value="MDB">MDB</option>
                    <option value="MED">MED</option>
                </select>
                @error('mastersCourses')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div id="doctorateCoursesDropdown" class="mb-4 hidden">
                <label for="doctorateCourses" class="block text-gray-700 text-sm sm:text-base">Doctorate Courses:</label>
                <select name="doctorateCourses" id="doctorateCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Doctorate Course</option>
                    <option value="PhD">PhD</option>
                    <option value="DBA">DBA</option>
                </select>
                @error('doctorateCourses')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 hidden" id="majorDropdown">
                <label for="major" class="block text-gray-700 text-sm sm:text-base">Major:</label>
                <select name="major" id="major" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Major</option>
                </select>
                @error('major')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="file" class="block text-gray-700 text-sm sm:text-base">Choose File:</label>
                <input type="file" name="file[]" id="file" class="form-input w-full  focus:outline-blue-400" multiple>
                @error('file')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring focus:border-blue-300">Submit</button>
            </div>

            <div  class="mb-2">
                <a href="{{ route('encoder.index') }}" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md focus:outline-none">Cancel</a>
            </div>
            
            @if(session('success'))
                <div class="text-green-600 mt-4 text-center text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeOfStudentSelect = document.getElementById('type_of_student');
        const undergradCoursesDropdown = document.getElementById('undergradCoursesDropdown');
        const postGradDegreeDropdown = document.getElementById('postGradDegreeDropdown');
        const mastersCoursesDropdown = document.getElementById('mastersCoursesDropdown');
        const doctorateCoursesDropdown = document.getElementById('doctorateCoursesDropdown');
        const majorDropdown = document.getElementById('majorDropdown');

        typeOfStudentSelect.addEventListener('change', function () {
            const selectedType = typeOfStudentSelect.value;
            
            // Reset all dropdowns to hidden state
            undergradCoursesDropdown.classList.add('hidden');
            postGradDegreeDropdown.classList.add('hidden');
            mastersCoursesDropdown.classList.add('hidden');
            doctorateCoursesDropdown.classList.add('hidden');
            majorDropdown.classList.add('hidden');

            if (selectedType === 'Undergraduate') {
                undergradCoursesDropdown.classList.remove('hidden');
            } else if (selectedType === 'Post Graduate') {
                postGradDegreeDropdown.classList.remove('hidden');
            }
        });

        const postGradDegreeSelect = document.getElementById('postGradDegrees');
        postGradDegreeSelect.addEventListener('change', function () {
            const selectedDegree = postGradDegreeSelect.value;

            // Reset courses dropdowns to hidden state
            mastersCoursesDropdown.classList.add('hidden');
            doctorateCoursesDropdown.classList.add('hidden');
            majorDropdown.classList.add('hidden');

            if (selectedDegree === 'Masters') {
                mastersCoursesDropdown.classList.remove('hidden');
            } else if (selectedDegree === 'Doctorate') {
                doctorateCoursesDropdown.classList.remove('hidden');
            }
        });

        const undergradCoursesSelect = document.getElementById('undergradCourses');
        undergradCoursesSelect.addEventListener('change', function () {
            const selectedCourse = undergradCoursesSelect.value;

            // Reset major dropdown to hidden state
            majorDropdown.classList.add('hidden');

            // Define courses and respective majors
            const courseMajorMap = {
                'BS IN BUSINESS ADMINISTRATION': ['Financial Management', 'Marketing Management'],
                'BACHELOR OF SECONDARY EDUCATION': ['Filipino', 'Math', 'English']
                // Add more mappings as needed
            };

            if (courseMajorMap[selectedCourse]) {
                populateMajorDropdown(courseMajorMap[selectedCourse]);
            }
        });

        function populateMajorDropdown(options) {
            const majorSelect = document.getElementById('major');
            majorSelect.innerHTML = ""; // Clear existing options

            options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                majorSelect.appendChild(optionElement);
            });

            // Show the major dropdown
            majorDropdown.classList.remove('hidden');
        }
    });
</script>

@endsection
