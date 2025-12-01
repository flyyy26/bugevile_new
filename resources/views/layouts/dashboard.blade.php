<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex">

        <!-- Sidebar (default tersembunyi) -->
        <aside id="sidebar" class="w-64 h-screen bg-white shadow-md transition-all duration-300 fixed left-0 top-0 -translate-x-64 z-50">
            <div class="p-3 font-bold text-xl border-b flex justify-between items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <nav class="mt-5">
                <a href="/dashboard" class="block py-3 px-6 hover:bg-gray-200 {{ Request::is('dashboard') ? 'bg-gray-300 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="/dashboard/orders" class="block py-3 px-6 hover:bg-gray-200 {{ Request::is('dashboard/orders') ? 'bg-gray-300 font-semibold' : '' }}">
                    Pesanan
                </a>
                <a href="/dashboard/pegawai" class="block py-3 px-6 hover:bg-gray-200 {{ Request::is('dashboard/pegawai') ? 'bg-gray-300 font-semibold' : '' }}">
                    Pegawai
                </a>
                <a href="/dashboard/spesifikasi" class="block py-3 px-6 hover:bg-gray-200 {{ Request::is('dashboard/spesifikasi') ? 'bg-gray-300 font-semibold' : '' }}">
                    Spesifikasi
                </a>
                <a href="/dashboard/pengaturan" class="block py-3 px-6 hover:bg-gray-200 {{ Request::is('dashboard/pengaturan') ? 'bg-gray-300 font-semibold' : '' }}">
                    Pengaturan
                </a>
            </nav>
        </aside>

        <!-- Main Content (default penuh layar) -->
        <main id="mainContent" class="w-full transition-all duration-300 overflow-x-hidden">
            
            <div class="pl-4 pt-6 flex items-start relative z-10">
                <!-- Toggle Button -->
                <button onclick="toggleSidebar()" id="hamburger"
                    class="bg-white transition-all duration-300 text-gray-800 px-3 py-1 font-medium rounded-lg text-sm shadow ">
                    ☰ Menu
                </button>
                
            </div>

            @yield('content')
        </main>

    </div>


    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');

            if (sidebar.classList.contains('-translate-x-64')) {
                // Tampilkan sidebar
                sidebar.classList.remove('-translate-x-64');
                hamburger.classList.remove('translate-x-0');
                hamburger.classList.add('translate-x-64');
            } else {
                // Sembunyikan sidebar
                sidebar.classList.add('-translate-x-64');
                hamburger.classList.remove('translate-x-64');
                hamburger.classList.add('translate-x-0');
            }
        }
    </script>

</body>
</html>
