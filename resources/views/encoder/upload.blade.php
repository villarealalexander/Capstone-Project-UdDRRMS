@extends('layouts.master-layout')

@section('title', 'Upload File')

@section('top-nav-links')
<a href="{{route('encoder.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house-user mr-1"></i>Back to Home
    </a>
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
                <input type="text" name="Name" id="Name" value="{{ old('Name') }}" placeholder="Enter student name" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full">
                @error('Name')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="BatchYear" class="block text-gray-700 text-sm sm:text-base">Batch Year:</label>
                <input type="text" name="BatchYear" id="BatchYear" value="{{ old('BatchYear') }}" placeholder="Enter batch year" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-input w-full">
                @error('BatchYear')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="type_of_student" class="block text-gray-700 text-sm sm:text-base">Type of Student:</label>
                <select name="type_of_student" id="type_of_student" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full" onchange="this.form.submit()">
                    <option value="">Select Type of Student</option>
                    <option value="Undergraduate" {{ old('type_of_student') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                    <option value="Post Graduate" {{ old('type_of_student') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                </select>
                @error('type_of_student')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            @if(old('type_of_student') == 'Undergraduate')
                <div class="mb-4">
                    <label for="undergradCourses" class="block text-gray-700 text-sm sm:text-base">Degree:</label>
                    <select name="undergradCourses" id="undergradCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full" onchange="this.form.submit()">
                        <option value="">Select Degree</option>
                        <option value="BS IN INFORMATION TECHNOLOGY" {{ old('undergradCourses') == 'BS IN INFORMATION TECHNOLOGY' ? 'selected' : '' }}>BS IN INFORMATION TECHNOLOGY</option>
                        <option value="BS IN COMPUTER SCIENCE" {{ old('undergradCourses') == 'BS IN COMPUTER SCIENCE' ? 'selected' : '' }}>BS IN COMPUTER SCIENCE</option>
                        <option value="BS IN ACCOUNTANCY" {{ old('undergradCourses') == 'BS IN ACCOUNTANCY' ? 'selected' : '' }}>BS IN ACCOUNTANCY</option>
                        <option value="BS IN BUSINESS ADMINISTRATION" {{ old('undergradCourses') == 'BS IN BUSINESS ADMINISTRATION' ? 'selected' : '' }}>BS IN BUSINESS ADMINISTRATION</option>
                        <option value="BS IN ELECTRONICS ENGINEERING" {{ old('undergradCourses') == 'BS IN ELECTRONICS ENGINEERING' ? 'selected' : '' }}>BS IN ELECTRONICS ENGINEERING</option>
                        <option value="BS IN CIVIL ENGINEERING" {{ old('undergradCourses') == 'BS IN CIVIL ENGINEERING' ? 'selected' : '' }}>BS IN CIVIL ENGINEERING</option>
                        <option value="BS IN ELECTRICAL ENGINEERING" {{ old('undergradCourses') == 'BS IN ELECTRICAL ENGINEERING' ? 'selected' : '' }}>BS IN ELECTRICAL ENGINEERING</option>
                        <option value="BS IN COMPUTER ENGINEERING" {{ old('undergradCourses') == 'BS IN COMPUTER ENGINEERING' ? 'selected' : '' }}>BS IN COMPUTER ENGINEERING</option>
                        <option value="BS IN NURSING" {{ old('undergradCourses') == 'BS IN NURSING' ? 'selected' : '' }}>BS IN NURSING</option>
                        <option value="BA IN COMMUNICATION" {{ old('undergradCourses') == 'BA IN COMMUNICATION' ? 'selected' : '' }}>BA IN COMMUNICATION</option>
                        <option value="BS IN PSYCHOLOGY" {{ old('undergradCourses') == 'BS IN PSYCHOLOGY' ? 'selected' : '' }}>BS IN PSYCHOLOGY</option>
                        <option value="BS IN HOSPITALITY MANAGEMENT" {{ old('undergradCourses') == 'BS IN HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>BS IN HOSPITALITY MANAGEMENT</option>
                        <option value="BS IN TOURISM MANAGEMENT" {{ old('undergradCourses') == 'BS IN TOURISM MANAGEMENT' ? 'selected' : '' }}>BS IN TOURISM MANAGEMENT</option>
                        <option value="BS IN CRIMINOLOGY" {{ old('undergradCourses') == 'BS IN CRIMINOLOGY' ? 'selected' : '' }}>BS IN CRIMINOLOGY</option>
                        <option value="BACHELOR OF SECONDARY EDUCATION" {{ old('undergradCourses') == 'BACHELOR OF SECONDARY EDUCATION' ? 'selected' : '' }}>BACHELOR OF SECONDARY EDUCATION</option>
                        <option value="BACHELOR OF ELEMENTARY EDUCATION" {{ old('undergradCourses') == 'BACHELOR OF ELEMENTARY EDUCATION' ? 'selected' : '' }}>BACHELOR OF ELEMENTARY EDUCATION</option>
                        <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION" {{ old('undergradCourses') == 'BACHELOR OF EARLY CHILDHOOD EDUCATION' ? 'selected' : '' }}>BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                        <option value="BACHELOR OF SPECIAL NEEDS EDUCATION" {{ old('undergradCourses') == 'BACHELOR OF SPECIAL NEEDS EDUCATION' ? 'selected' : '' }}>BACHELOR OF SPECIAL NEEDS EDUCATION</option>

                    </select>
                    @error('undergradCourses')
                        <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if(old('type_of_student') == 'Post Graduate')
                <div class="mb-4">
                    <label for="postGradDegrees" class="block text-gray-700 text-sm sm:text-base">Degree Level:</label>
                    <select name="postGradDegrees" id="postGradDegrees" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full" onchange="this.form.submit()">
                        <option value="">Select Degree Type</option>
                        <option value="Masters" {{ old('postGradDegrees') == 'Masters' ? 'selected' : '' }}>Masters</option>
                        <option value="Doctorate" {{ old('postGradDegrees') == 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                    </select>
                    @error('postGradDegrees')
                        <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if(old('postGradDegrees') == 'Masters')
                <div class="mb-4">
                    <label for="mastersCourses" class="block text-gray-700 text-sm sm:text-base">Masters Courses:</label>
                    <select name="mastersCourses" id="mastersCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                        <option value="">Select Masters Course</option>
                        <option value="MBA" {{ old('mastersCourses') == 'MBA' ? 'selected' : '' }}>MBA</option>
                        <option value="MAED" {{ old('mastersCourses') == 'MAED' ? 'selected' : '' }}>MAED</option>
                        <option value="MIT" {{ old('mastersCourses') == 'MIT' ? 'selected' : '' }}>MIT</option>
                        <option value="MDB" {{ old('mastersCourses') == 'MDB' ? 'selected' : '' }}>MDB</option>
                        <option value="MED" {{ old('mastersCourses') == 'MED' ? 'selected' : '' }}>MED</option>
                    </select>
                    @error('mastersCourses')
                        <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if(old('postGradDegrees') == 'Doctorate')
                <div class="mb-4">
                    <label for="doctorateCourses" class="block text-gray-700 text-sm sm:text-base">Doctorate Courses:</label>
                    <select name="doctorateCourses" id="doctorateCourses" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                        <option value="">Select Doctorate Course</option>
                        <option value="PhD" {{ old('doctorateCourses') == 'PhD' ? 'selected' : '' }}>PhD</option>
                        <option value="DBA" {{ old('doctorateCourses') == 'DBA' ? 'selected' : '' }}>DBA</option>
</select>
@error('doctorateCourses')
<div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
@enderror
</div>
@endif

@if(old('undergradCourses') == 'BS IN BUSINESS ADMINISTRATION')
            <div class="mb-4">
                <label for="major" class="block text-gray-700 text-sm sm:text-base">Major:</label>
                <select name="major" id="major" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Major</option>
                    <option value="Financial Management" {{ old('major') == 'Financial Management' ? 'selected' : '' }}>Financial Management</option>
                    <option value="Marketing Management" {{ old('major') == 'Marketing Management' ? 'selected' : '' }}>Marketing Management</option>
                </select>
                @error('major')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>
        @endif

        @if(old('undergradCourses') == 'BACHELOR OF SECONDARY EDUCATION')
            <div class="mb-4">
                <label for="major" class="block text-gray-700 text-sm sm:text-base">Major:</label>
                <select name="major" id="major" class="border-2 border-gray-500 p-1 focus:outline-blue-400 rounded-md form-select w-full">
                    <option value="">Select Major</option>
                    <option value="Filipino" {{ old('major') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                    <option value="Math" {{ old('major') == 'Math' ? 'selected' : '' }}>Math</option>
                    <option value="English" {{ old('major') == 'English' ? 'selected' : '' }}>English</option>
                </select>
                @error('major')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <div class="mb-4">
            <label for="file" class="block text-gray-700 text-sm sm:text-base">Choose File:</label>
            <input type="file" name="file[]" id="file" class="form-input w-full focus:outline-blue-400" multiple>
            @error('file')
                <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
            @enderror
            @error ('file.*')
                <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex justify-start font-medium">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 mr-1 rounded-md focus:outline-none focus:ring focus:border-green-300">Submit</button>
            <a href="{{ route('encoder.index') }}" class=" hover:underline text-blue-500 py-2 px-4 rounded-md focus:outline-none">Cancel</a>
        </div>

        @if(session('success'))
            <div class="text-green-600 mt-4 text-center text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>
</form>
</div>
@endsection
