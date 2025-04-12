@section('page_title', 'Dashboard Overview')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="icon" type="image/png" href="{{ asset('productImages/icon.png') }}">
    <!-- CSRF Token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modern Admin Dashboard</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js for the bar chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="relative bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden" x-data>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed inset-y-0 z-20 flex flex-col bg-white dark:bg-gray-800 shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 cursor-pointer">
                <!-- AdminPro Text (Hidden when collapsed) -->
                <div id="adminProText" class="text-2xl font-bold text-brand-600 dark:text-brand-400" onclick="window.location.href='/admin/dashboard'">AdminPro</div>
                
                <!-- Button to toggle sidebar visibility -->
                <button id="toggleSidebar" class="text-gray-600 dark:text-gray-300 focus:outline-none">
                    <!-- Menu Icon (will be shown when sidebar is hidden) -->
                    <svg id="menuIcon" class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <!-- X Icon (will be shown when sidebar is visible) -->
                    <svg id="closeIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu items (Initially hidden when sidebar is collapsed) -->
            <nav id="sidebarMenu" class="flex flex-1 flex-col overflow-y-auto p-4">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="/admin/statistics" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-50 dark:bg-brand-900 text-brand-600 dark:text-brand-400 font-medium">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h8v8H3V3zm10 0h8v5h-8V3zM3 13h5v8H3v-8zm7 0h11v8H10v-8z" />
                            </svg>
                            <span id="menuText" class="block">Statistics</span> <!-- Hidden when collapsed -->
                        </a>
                    </li>

                    <!-- Product -->
                    <li>
                        <a href="/admin/products" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-50 dark:bg-brand-900 text-brand-600 dark:text-brand-400 font-medium">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73zM5.14 6.39L12 2.69l6.86 3.7L12 10.09 5.14 6.39zM5 8.21l7 3.78v7.81l-7-4V8.21zm14 7.59l-7 4v-7.81l7-3.78v7.59z" />
                            </svg>
                            <span id="menuText" class="block">Quản lý sản phẩm</span>
                        </a>
                    </li>

                    <!-- Category -->
                    <li>
                        <a href="/admin/categories" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-50 dark:bg-brand-900 text-brand-600 dark:text-brand-400 font-medium">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2zm0 13L3 10.18v2.13l9 4.09 9-4.09v-2.13L12 15z" />
                            </svg>
                            <span id="menuText" class="block">Quản lý danh mục</span>
                        </a>
                    </li>

                    <!-- Quản lý người dùng -->
                    <li>
                        <a href="/admin/users" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-50 dark:bg-brand-900 text-brand-600 dark:text-brand-400 font-medium">
                            <!-- Sửa Icon ở đây -->
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6h2V7zm-2 8h2v2h-2z"/>
                            </svg>
                            <span id="menuText" class="block">Quản lý người dùng</span>
                        </a>
                    </li>
                    <!-- //Quản lý sự kiện -->
                    <li>
                        <a href="/admin/events" class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-50 dark:bg-brand-900 text-brand-600 dark:text-brand-400 font-medium">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 
                                2v14c0 1.1.9 2 2 2h14c1.1 
                                0 2-.9 2-2V6c0-1.1-.9-2-2-2zM5 
                                20V9h14v11H5zM7 11h5v5H7v-5z"/>
                            </svg>

                            <span id="menuText" class="block">Quản lý sự kiện</span>
                        </a>
                    </li>


                </ul>
            </nav>
        </aside>


        <!-- Content Area -->
        <div class="flex-1 ml-64 p-8 pt-1 overflow-y-auto">
            <!-- Header -->
            <header class="flex items-center justify-between mb-4 header-gradient">
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-100">@yield('page_title', 'Dashboard Overview')</h1>
                <div class="flex items-center gap-4">
                    <button id="darkModeToggle" class="p-2 rounded-full border border-brand-500 dark:border-brand-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <svg id="darkModeIcon" class="h-5 w-5 text-black-600 dark:text-black-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- Trigger -->
                    <div>
                        <button @click="$store.dropdown.toggle()" class="flex items-center gap-2 focus:outline-none">
                            <img id="userAvatar" src="https://randomuser.me/api/portraits/men/1.jpg" alt="User"
                                class="h-10 w-10 rounded-full border-2 border-brand-500">
                            <span data-admin-name class="text-sm font-medium text-gray-700 dark:text-gray-300">Loading...</span>
                            <svg class="w-4 h-4 transition-transform duration-300 ease-in-out text-black-500 dark:text-black-300"
                                :class="{ 'rotate-180': $store.dropdown.open }"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.061l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>



                </div>
                @yield('dashboard')
            </header>
            <!-- Dropdown content -->
            <div x-show="$store.dropdown.open"
                x-cloak
                @click.away="$store.dropdown.close()"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="fixed top-20 right-16 w-64 bg-white dark:bg-gray-800 shadow-xl rounded-xl p-4 z-[100]">
                <div class="mb-4">
                    <h3 data-admin-name class="font-semibold text-gray-800 dark:text-white text-base">Loading ...</h3>
                    <p data-admin-email class="text-sm text-gray-500 dark:text-gray-400">Loading ...</p>
                </div>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-brand-600">👤 Profile</a></li>
                    <li><a href="#" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-brand-600">⚙️ Account settings</a></li>
                    <li><a href="#" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-brand-600">❓ Support</a></li>
                    <li>
                        <hr class="border-t dark:border-gray-600 my-2">
                    </li>
                    <li>
                        <button onclick="logout()" class="w-full text-left flex items-center gap-2 text-red-500 hover:text-red-600">
                            🚪 Sign out
                        </button>
                    </li>
                </ul>
            </div>

            <div class="">
                @yield('products')
            </div>
            <div class="">
                @yield('categories')
            </div>
            <div class="">
                @yield('statistics')
            </div>
            <div class="">
                @yield('orders')
            </div>
            <div class="">
                @yield('users')
            </div>
            <div class="">
                @yield('events')
            </div>
            @if (Request::is('admin/dashboard'))
            @section('dashboard')
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">Chào mừng đến với Admin Dashboard!</h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        Đây là khu vực tổng quan nơi bạn có thể theo dõi các chỉ số quan trọng, quản lý sản phẩm và danh mục một cách hiệu quả. Hãy sử dụng sidebar bên trái để truy cập các chức năng quản trị.
                    </p>
                </div>
            </div>
            @endsection
            <div class="">
                @yield('dashboard')
            </div>
            @endif


        </div>
    </div>




    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('dropdown', {
                open: false,
                toggle() {
                    this.open = !this.open;
                },
                close() {
                    this.open = false;
                }
            });
        });
    </script>
    <script>
        // Logout Functionality
        function logout() {
            localStorage.removeItem('access_token');
            window.location.href = '/login';
        }

        if (!localStorage.getItem('access_token')) {
            window.location.href = '/login';
        } else {
            fetch('/api/auth/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.roles.includes('Admin')) {
                        console.log('Authenticated:', data);
                        document.querySelectorAll('[data-admin-name]').forEach(el => {
                            el.innerText = data.user.name;
                        });
                        document.querySelectorAll('[data-admin-email]').forEach(el => {
                            el.innerText = data.user.email;
                        });

                    } else {
                        window.location.href = '/login';
                    }
                })
                .catch(error => {
                    console.error('Error during authentication:', error);
                    window.location.href = '/login';
                });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy đường dẫn hiện tại
            const currentPath = window.location.pathname;

            // Lấy tất cả các liên kết menu
            const menuLinks = document.querySelectorAll('.menu-item');

            // Duyệt qua từng liên kết và kiểm tra khớp với đường dẫn hiện tại
            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Sidebar toggle logic
            const toggleSidebarButton = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const sidebarMenu = document.getElementById('sidebarMenu');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');
            const adminProText = document.getElementById('adminProText');
            const menuText = document.querySelectorAll('#menuText');

            toggleSidebarButton.addEventListener('click', () => {
                // Toggle the sidebar visibility
                sidebar.classList.toggle('w-16');
                sidebarMenu.classList.toggle('hidden'); // Only hide the menu items (icons remain visible)

                // Toggle icons visibility
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
                adminProText.classList.toggle('hidden'); // Hide AdminPro text when collapsed

                // Hide the text for each menu item
                menuText.forEach(item => item.classList.toggle('hidden'));
            });
        });
    </script>
    @vite('resources/js/app.js')
</body>

</html>