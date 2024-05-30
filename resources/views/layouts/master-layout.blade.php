<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - UdD Registrar Repository Management System</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-cover bg-fixed bg-blue-200 font-poppins">
    <div class="flex h-screen">
        <div class="bg-blue-900 w-48 py-2 flex flex-col justify-between">
            <div>
                <div class="flex flex-col items-center">
                    <img class="w-20" src="{{ asset('images/UDD_LOGO.png') }}" alt="UdD Logo">
                    <h1 class="text-sm font-medium  text-white text-center mb-2 border-b-2 border-gray-400 shadow-md p-1 ">@yield('header-title', 'UdD Registrar Records Repository Management System')</h1>
                </div>
                <nav class="mt-4 space-y-6 text-center flex flex-col">
                    @yield('top-nav-links')
                </nav>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class= "ml-10 text-white hover:bg-red-500 px-2 rounded-lg text-lg font-bold mb-10">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center overflow-y-auto">
            <div class="bg-blue-200 shadow-lg rounded-lg min-w-full min-h-full">
                @if(isset($role) && isset($name))
                    <div class="text-3xl text-center text-black font-bold mt-10">
                        <i class="fa-solid fa-user mr-2"></i>Welcome {{ ucfirst($role) }}, {{ $name }}
                    </div>
                @endif

                <!-- Page Title -->
                <div class="text-2xl text-center text-black font-bold my-6">
                    @yield('page-title')
                </div>

                <div class="px-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
