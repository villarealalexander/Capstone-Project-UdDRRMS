<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-poppins bg-gradient-to-r from-cyan-500 to-blue-500">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-blue-200 bg-opacity-80 p-8 rounded-xl shadow-2xl w-full sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-1/3 md:shrink-0">
            <h1 class="text-3xl font-normal text-center mb-4">UdD Registrar Records Repository System</h1>
            <form action="{{ route('login') }}" method="POST" class="space-y-6" novalidate>
                @csrf
                <script src="resources/js/app.js"></script>

                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="w-48 p-2 flex-shrink-0">
                        <img class="w-full" src="{{ asset('images/UDD_LOGO.png') }}" alt="UdD Logo">
                    </div>
                    <div class="flex flex-1 flex-col">
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" class="block w-full p-2 h-10 border-2 rounded-md focus:outline-blue-400 @error('email') border-red-500 @enderror" 
                                value="{{ old('email') }} " autocomplete="on" >
                            <span id="email-error" class="text-red-500 text-sm"></span>
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
                        <div class="text-left mb-2">
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 underline hover:text-gray-900">Forgot password?</a>
                        </div>
                        <button type="submit" class="w-full h-10 bg-black rounded-md px-10 text-lg font-bold text-white">Login</button>
                        @if (session('status'))
                            <div class="alert alert-success text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
