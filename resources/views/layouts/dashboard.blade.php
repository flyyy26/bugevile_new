<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Tailwind CDN -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="flex">

        <!-- Sidebar (default tersembunyi) -->
        <aside id="sidebar" class="sidebar_layout">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <nav class="sidebar_nav">
                <div class="sidebar_nav_layout">
                    <a href="/dashboard" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="/dashboard/orders" class="{{ Request::is('dashboard/orders') ? 'active' : '' }}">
                        Pesanan
                    </a>
                    <a href="/dashboard/pegawai" class="{{ Request::is('dashboard/pegawai') ? 'active' : '' }}">
                        Pegawai
                    </a>
                    <a href="/dashboard/affiliator" class="{{ Request::is('dashboard/affiliator') ? 'active' : '' }}">
                        Affiliator
                    </a>
                    <a href="/dashboard/pelanggan" class="{{ Request::is('dashboard/pelanggan') ? 'active' : '' }}">
                        Pelanggan
                    </a>
                    <a href="/dashboard/spesifikasi" class="{{ Request::is('dashboard/spesifikasi') ? 'active' : '' }}">
                        Spesifikasi
                    </a>
                    <a href="/dashboard/pengaturan" class="{{ Request::is('dashboard/pengaturan') ? 'active' : '' }}">
                        Pengaturan
                    </a>
                </div>
                <div class="logout_layout">
                    <form action="/dashboard/logout" method="POST">
                        @csrf
                        <button class="bg-red-500">
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content (default penuh layar) -->
        <main id="mainContent">
            
            <div class="sidebar_btn_wrapper">
                <!-- Toggle Button -->
                <button onclick="toggleSidebar()" id="hamburger" class="sidebar_hamburger">
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

            
            sidebar.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                hamburger.classList.add('geser-kanan'); 
            } else {
                hamburger.classList.remove('geser-kanan');
            }
        }
    </script>

</body>
</html>
