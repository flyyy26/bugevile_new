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
                    @if(Auth::user()->role === 'admin')
                        <a href="/dashboard/total-transaksi" class="{{ Request::is('dashboard/total-transaksi') ? 'active' : '' }}">
                            Belanja
                        </a>
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <li style="list-style:none;" class="menu-item {{ Request::is('dashboard/pengaturan*','dashboard/pegawai*','dashboard/affiliator*','dashboard/pelanggan*') ? 'open' : '' }}">
                            <a href="javascript:void(0)" class="menu-link toggle-submenu">
                                <span>Pengaturan</span>
                                <span class="arrow">▾</span>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="/dashboard/pengaturan"
                                    class="{{ Request::is('dashboard/pengaturan') ? 'active' : '' }}">
                                        Pengaturan Umum
                                    </a>
                                </li>

                                <li>
                                    <a href="/dashboard/pegawai"
                                    class="{{ Request::is('dashboard/pegawai') ? 'active' : '' }}">
                                        Pegawai
                                    </a>
                                </li>

                                <li>
                                    <a href="/dashboard/pelanggan"
                                    class="{{ Request::is('dashboard/pelanggan') ? 'active' : '' }}">
                                        Pelanggan
                                    </a>
                                </li>
                            </ul>
                        </li>

                    @endif
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
            
            <div class="sidebar_btn_wrapper hidden_print">

                <div class="sidebar_btn_layout">
                    <!-- Toggle Button -->
                    <button onclick="toggleSidebar()" id="hamburger" class="sidebar_hamburger">
                        ☰ Menu
                    </button>
                    <button class="sidebar_hamburger" onclick="goBack()">Kembali</button>
                </div>
            </div>

            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.toggle-submenu');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const parent = this.closest('.menu-item');
                    parent.classList.toggle('open');
                });
            });
        });
    </script>


    <script>
         function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');

            sidebar.classList.toggle('active');
            hamburger.classList.toggle('geser-kanan');
        }

        function resetSidebar() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');

            sidebar.classList.remove('active');
            hamburger.classList.remove('geser-kanan');
        }

        window.addEventListener('pageshow', function () {
            resetSidebar();
        });

        function goBack() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');

            // pastikan sidebar ditutup
            sidebar.classList.remove('active');
            hamburger.classList.remove('geser-kanan');

            // kembali ke halaman sebelumnya
            history.back();
        }
    </script>


</body>
</html>
