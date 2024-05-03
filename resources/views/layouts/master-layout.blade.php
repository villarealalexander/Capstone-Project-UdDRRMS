<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - UdD Registrar Records Repository System</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-cover bg-fixed bg-blue-200 font-poppins">
    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 py-1 px-6 flex justify-between items-center">
        <div class="flex items-center">
            <img class="w-20" src="{{ asset('images/UDD_LOGO.png') }}" alt="UdD Logo">
            <div class="ml-3"> <!-- Added a margin for spacing -->
                <h1 class="text-lg font-semibold">@yield('header-title', 'UdD Registrar Records Repository System')</h1>
                @if(isset($role) && isset($name))
                    <div class="text-lg text-white  px-2 py-1 rounded-md text-center">
                        Welcome {{ ucfirst($role) }}, {{ $name }}
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center space-x-4 md:space-x-2 relative">
            <button id="toggle-menu" class="text-white focus:outline-none relative lg:hidden xl:hidden">
                <i class="fas fa-bars text-white text-3xl"></i>
            </button>

            <div id="dropdown-menu" class="hidden rounded-lg bg-gray-50 w-max py-4 px-6 absolute top-full right-0 mt-2 z-10">
                <div class="flex flex-col items-start space-y-2">
                    @yield('top-nav-links')
                    <form class="text-black font-bold flex justify-center" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Logout</button>
                    </form> 
                </div>
            </div>
            <!-- Navigation Links (Visible on lg and xl screens) -->
            <div class="hidden lg:flex xl:flex items-center justify-center space-x-2">
                @yield('top-nav-links')
                <form class="text-black font-bold" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>

    @yield('content')

    <script>
        document.getElementById('toggle-menu').addEventListener('click', function () {
            document.getElementById('dropdown-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
