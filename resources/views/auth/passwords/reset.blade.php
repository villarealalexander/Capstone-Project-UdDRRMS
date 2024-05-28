<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-poppins bg-gradient-to-r from-cyan-500 to-blue-500">
<div class="flex justify-center items-center min-h-screen">
        <div class="bg-blue-200 bg-opacity-60 p-8 rounded-xl shadow-2xl w-full sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-1/3 md:shrink-0">
            <h1 class="text-3xl font-normal text-center mb-4">Reset Password</h1>
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" class="block w-full p-2 h-10 border-2 rounded-md focus:outline-blue-400 @error('email') border-red-500 @enderror" value="{{ old('email') }}" autocomplete="on">
                    @error('email')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="block w-full p-2 h-10 border-2 rounded-md focus:outline-blue-400 @error('password') border-red-500 @enderror">
                    @error('password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="block w-full p-2 h-10 border-2 rounded-md focus:outline-blue-400">
                </div>
                <button type="submit" class="w-full h-10 bg-black rounded-md px-10 text-lg font-bold text-white">Reset Password</button>
            </form>
        </div>
    </div>
</body>