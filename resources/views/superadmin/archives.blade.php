@extends('layouts.master-layout')

@section('title', 'Archived Users')

@section('top-nav-links')
    <a href="{{ route('superadmin.index') }}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
        <i class="fa-solid fa-house mr-1"></i>Back to Home
    </a>
@endsection

@section('page-title')
<i class="fas fa-archive mr-2"></i>Archived Users
@endsection

@section('content')
    <div class="mx-auto flex flex-col items-center sm:w-11/12 md:w-4/5 lg:w-3/4 xl:w-5/6">
        <!-- Container for the success message -->
        <div class="flex justify-center w-full my-4 h-8">
            @if (session('success'))
                <div class="text-green-500 font-bold text-lg">{{ session('success') }}</div>
            @endif
        </div>

        <main class="w-full overflow-x-auto" style="max-height: 500px">
            <table class="min-w-full mt-4 bg-gray-50 bg-opacity-30 border border-gray-300 shadow-md text-xl">
                <thead class="bg-white sticky top-0">
                    <tr>
                        <th class= "py-2 px-4 border-b text-black ">Name</th>
                        <th class= "py-2 px-4 border-b text-black">Email</th>
                        <th class= "py-2 px-4 border-b text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($archivedUsers as $user)
                        <tr class="border-b-2 border-gray-400 text-center">
                            <td class="py-2 px-12 border-b">{{ $user->name }}</td>
                            <td class="text-center py-2 px-12 border-b">{{ $user->email }}</td>
                            <td class="py-2 px-12 border-b text-center">
                                <form action="{{ route('superadmin.restore', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Restore</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
@endsection
