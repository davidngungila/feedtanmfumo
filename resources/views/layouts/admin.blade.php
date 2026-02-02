<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="FEEDTAN DIGITAL Admin - Community Feedback & Microfinance Management Platform" name="description">
    <title>@yield('page-title', 'Admin Dashboard') - FEEDTAN DIGITAL</title>
    <base href="/">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <style>
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hJFQNcOM.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
        }
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hJVQNcOM.woff2) format('woff2');
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hK1QN.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full bg-[#fafafa] text-[#41546b] overflow-hidden">
    <div class="h-full flex flex-col lg:flex-row">
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#015425] text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-[#013019]">
                <h1 class="text-2xl font-bold text-white">FeedTan Digital</h1>
                <button id="close-sidebar" class="lg:hidden absolute top-4 right-4 text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
                @include('components.admin-menu-items')
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-0 overflow-hidden">
            <!-- Fixed Header -->
            <header class="fixed lg:static top-0 left-0 right-0 lg:right-auto bg-white shadow-sm z-30 lg:z-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="menu-toggle" class="lg:hidden mr-4 text-[#015425] hover:text-[#013019]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h2 class="text-xl lg:text-2xl font-bold text-[#015425]">@yield('page-title', 'Admin Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div id="notification-container" class="relative">
                            <button id="notification-button" class="relative p-2 text-gray-600 hover:text-[#015425] hover:bg-gray-100 rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span id="notification-badge" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto">
                                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    <button id="mark-all-read" class="text-xs text-[#015425] hover:underline">Mark all as read</button>
                                </div>
                                <div id="notification-list" class="py-2">
                                    <!-- Notifications will be loaded here via AJAX -->
                                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                        <div class="animate-spin inline-block w-4 h-4 border-2 border-[#015425] border-t-transparent rounded-full"></div>
                                        <span class="ml-2">Loading notifications...</span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-[#015425] hover:underline">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div id="user-menu-container" class="relative user-profile-dropdown">
                            <button id="user-menu-button" class="flex items-center space-x-3 p-1 rounded-full hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 hidden md:block user-menu-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('admin.profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('admin.profile.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                </div>
                                <div class="border-t border-gray-200 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto pt-16 lg:pt-0 flex flex-col">
                <div class="flex-1 p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
                
                <!-- Footer -->
                <footer class="bg-white border-t border-gray-200 mt-auto">
                    <div class="px-4 sm:px-6 lg:px-8 py-3">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600">
                            <span>&copy; {{ date('Y') }} FeedTan CMG. All rights reserved.</span>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <span class="text-gray-400">Version 1.0.0</span>
                                <span class="hidden sm:inline text-gray-300">|</span>
                                <span class="hidden sm:inline">Powered by FeedTan Team</span>
                            </div>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Notification Component -->
    @include('components.notification')

    @stack('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            // Toggle sidebar
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            // Dropdown click functionality
            const dropdownContainers = document.querySelectorAll('.dropdown-container');
            dropdownContainers.forEach(container => {
                const toggleButton = container.querySelector('.dropdown-toggle');
                const menu = container.querySelector('.dropdown-menu');
                const arrow = container.querySelector('.dropdown-arrow');
                
                if (toggleButton) {
                    toggleButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const isHidden = menu?.classList.contains('hidden');
                        
                        // Close all other dropdowns
                        dropdownContainers.forEach(otherContainer => {
                            if (otherContainer !== container) {
                                const otherMenu = otherContainer.querySelector('.dropdown-menu');
                                const otherArrow = otherContainer.querySelector('.dropdown-arrow');
                                if (otherMenu && otherArrow) {
                                    otherMenu.classList.add('hidden');
                                    otherArrow.classList.remove('rotate-180');
                                    // Close nested dropdowns
                                    otherMenu.querySelectorAll('.nested-dropdown-menu').forEach(nested => nested.classList.add('hidden'));
                                    otherMenu.querySelectorAll('.nested-dropdown-arrow').forEach(nestedArrow => nestedArrow.classList.remove('rotate-180'));
                                }
                            }
                        });
                        
                        // Toggle current dropdown
                        if (menu && arrow) {
                            if (isHidden) {
                                menu.classList.remove('hidden');
                                arrow.classList.add('rotate-180');
                            } else {
                                menu.classList.add('hidden');
                                arrow.classList.remove('rotate-180');
                                // Close nested dropdowns
                                menu.querySelectorAll('.nested-dropdown-menu').forEach(nested => nested.classList.add('hidden'));
                                menu.querySelectorAll('.nested-dropdown-arrow').forEach(nestedArrow => nestedArrow.classList.remove('rotate-180'));
                            }
                        }
                    });
                }
            });

            // Nested dropdown click functionality
            const nestedDropdownContainers = document.querySelectorAll('.nested-dropdown-container');
            nestedDropdownContainers.forEach(container => {
                const toggleButton = container.querySelector('.nested-dropdown-toggle');
                const menu = container.querySelector('.nested-dropdown-menu');
                const arrow = container.querySelector('.nested-dropdown-arrow');
                
                if (toggleButton) {
                    toggleButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const isHidden = menu?.classList.contains('hidden');
                        
                        // Close other nested dropdowns in the same parent
                        const parentDropdown = container.closest('.dropdown-menu');
                        if (parentDropdown) {
                            parentDropdown.querySelectorAll('.nested-dropdown-menu').forEach(m => {
                                if (m !== menu) {
                                    m.classList.add('hidden');
                                    const nestedArrow = m.closest('.nested-dropdown-container')?.querySelector('.nested-dropdown-arrow');
                                    if (nestedArrow) {
                                        nestedArrow.classList.remove('rotate-180');
                                    }
                                }
                            });
                        }
                        
                        // Toggle current nested dropdown
                        if (menu && arrow) {
                            if (isHidden) {
                                menu.classList.remove('hidden');
                                arrow.classList.add('rotate-180');
                            } else {
                                menu.classList.add('hidden');
                                arrow.classList.remove('rotate-180');
                            }
                        }
                    });
                }
            });

            // Notification dropdown toggle
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationBadge = document.getElementById('notification-badge');
            const markAllReadButton = document.getElementById('mark-all-read');

            // Function to update notification badge
            function updateNotificationBadge(count) {
                if (notificationBadge) {
                    if (count === 0) {
                        notificationBadge.style.display = 'none';
                    } else {
                        notificationBadge.style.display = 'block';
                        notificationBadge.textContent = count > 99 ? '99+' : count;
                    }
                }
            }

            // Function to load notifications
            function loadNotifications() {
                fetch('{{ route("admin.notifications.index") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notification-list');
                    if (!notificationList) return;

                    if (data.notifications && data.notifications.length > 0) {
                        notificationList.innerHTML = data.notifications.map(notif => {
                            const dotColor = notif.is_read ? 'bg-gray-300' : (notif.color === 'orange' ? 'bg-orange-500' : 'bg-blue-500');
                            const titleColor = notif.is_read ? 'text-gray-600' : 'text-gray-900';
                            const linkAttr = notif.link ? `onclick="window.location.href='${notif.link}'"` : '';
                            
                            return `
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer notification-item" 
                                     data-read="${notif.is_read}" 
                                     data-id="${notif.id}"
                                     ${linkAttr}>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-2 h-2 mt-2 ${dotColor} rounded-full"></div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium ${titleColor}">${notif.icon || '🔔'} ${notif.title}</p>
                                            <p class="text-xs text-gray-500 mt-1">${notif.message}</p>
                                            <p class="text-xs text-gray-400 mt-1">${notif.time_ago}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        notificationList.innerHTML = '<div class="px-4 py-3 text-center text-gray-500 text-sm">No new notifications</div>';
                    }

                    updateNotificationBadge(data.unread_count || 0);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const notificationList = document.getElementById('notification-list');
                    if (notificationList) {
                        notificationList.innerHTML = '<div class="px-4 py-3 text-center text-red-500 text-sm">Error loading notifications</div>';
                    }
                });
            }

            // Load notifications on page load
            loadNotifications();
            
            // Reload notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            const notificationContainer = document.getElementById('notification-container');
            if (notificationButton && notificationDropdown && notificationContainer) {
                let hoverTimeout;
                
                function showDropdown() {
                    if (hoverTimeout) {
                        clearTimeout(hoverTimeout);
                    }
                    // Close all other dropdowns
                    document.getElementById('user-menu-dropdown')?.classList.add('hidden');
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        m.classList.add('hidden');
                        m.closest('.dropdown-container')?.querySelector('.dropdown-arrow')?.classList.remove('rotate-180');
                    });
                    // Show notification dropdown
                    notificationDropdown.classList.remove('hidden');
                }

                function hideDropdown() {
                    hoverTimeout = setTimeout(function() {
                        notificationDropdown.classList.add('hidden');
                    }, 150);
                }

                // Show dropdown on hover over container (button or dropdown)
                notificationContainer.addEventListener('mouseenter', showDropdown);
                
                // Hide dropdown when mouse leaves container
                notificationContainer.addEventListener('mouseleave', hideDropdown);

                // Mark all as read functionality
                if (markAllReadButton) {
                    markAllReadButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        fetch('{{ route("admin.notifications.mark-all-read") }}', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload notifications to reflect changes
                                loadNotifications();
                            }
                        })
                        .catch(error => {
                            console.error('Error marking all as read:', error);
                        });
                    });
                }

                // Mark individual notification as read on click (using event delegation)
                document.addEventListener('click', function(e) {
                    const notificationItem = e.target.closest('.notification-item');
                    if (notificationItem && notificationItem.getAttribute('data-read') === 'false') {
                        const notificationId = notificationItem.getAttribute('data-id');
                        
                        if (notificationId) {
                            fetch(`{{ url('admin/notifications') }}/${notificationId}/mark-read`, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Reload notifications to reflect changes
                                    loadNotifications();
                                }
                            })
                            .catch(error => {
                                console.error('Error marking notification as read:', error);
                            });
                        }
                    }
                });

            }

            // User profile dropdown toggle
            const userMenuContainer = document.getElementById('user-menu-container');
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuArrow = document.querySelector('.user-menu-arrow');

            if (userMenuContainer && userMenuButton && userMenuDropdown) {
                let userHoverTimeout;
                
                function showUserDropdown() {
                    if (userHoverTimeout) {
                        clearTimeout(userHoverTimeout);
                    }
                    // Close notification dropdown
                    if (notificationDropdown) {
                        notificationDropdown.classList.add('hidden');
                    }
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== userMenuDropdown) {
                            m.classList.add('hidden');
                            m.closest('.dropdown-container')?.querySelector('.dropdown-arrow')?.classList.remove('rotate-180');
                        }
                    });

                    // Show user menu
                    userMenuDropdown.classList.remove('hidden');
                    if (userMenuArrow) {
                        userMenuArrow.classList.add('rotate-180');
                    }
                }

                function hideUserDropdown() {
                    userHoverTimeout = setTimeout(function() {
                        userMenuDropdown.classList.add('hidden');
                        if (userMenuArrow) {
                            userMenuArrow.classList.remove('rotate-180');
                        }
                    }, 150);
                }

                // Show dropdown on hover over container
                userMenuContainer.addEventListener('mouseenter', showUserDropdown);
                
                // Hide dropdown when mouse leaves container
                userMenuContainer.addEventListener('mouseleave', hideUserDropdown);
            }
        });
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @stack('scripts')
    
    <!-- Quick Contact Widget -->
    @include('components.quick-contact-widget')
</body>
</html>
