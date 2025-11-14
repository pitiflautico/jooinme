<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'JoinMe') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link href="{{ asset('ki-admin/images/favicon.svg') }}" rel="icon" type="image/svg+xml">

    <!-- Animation CSS -->
    <link href="{{ asset('ki-admin/vendor/animate.min.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('ki-admin/vendor/tabler-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/vendor/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/vendor/simplebar.css') }}" rel="stylesheet" type="text/css">

    <!-- Main CSS -->
    <link href="{{ asset('ki-admin/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/responsive.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
</head>
<body>
<div class="app-wrapper">

    <!-- Loader -->
    @if(!request()->ajax())
    <div class="loader-wrapper">
        <div class="loader_24"></div>
    </div>
    @endif

    <!-- Menu Navigation starts -->
    <nav>
        <div class="app-logo">
            <a class="logo d-inline-block" href="{{ route('dashboard') }}">
                <img alt="{{ config('app.name') }}" src="{{ asset('images/logo.svg') }}" style="max-height: 40px;">
            </a>

            <span class="bg-light-primary toggle-semi-nav d-flex-center">
                <i class="ti ti-chevron-right"></i>
            </span>

            <div class="d-flex align-items-center nav-profile p-3">
                <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-primary m-auto">
                    @if(auth()->user()->avatar)
                    <img alt="{{ auth()->user()->name }}" class="img-fluid b-r-10" src="{{ Storage::url(auth()->user()->avatar) }}">
                    @else
                    <span class="text-white fw-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                </span>
                <div class="flex-grow-1 ps-2">
                    <h6 class="text-primary mb-0">{{ auth()->user()->name }}</h6>
                    <p class="text-muted f-s-12 mb-0">{{ auth()->user()->email }}</p>
                </div>

                <div class="dropdown profile-menu-dropdown">
                    <a aria-expanded="false" data-bs-auto-close="true" data-bs-toggle="dropdown" role="button">
                        <i class="ti ti-settings fs-5"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                            <a class="f-w-500" href="{{ route('profile.edit') }}">
                                <i class="ti ti-user-circle pe-1 f-s-20"></i> Profile Details
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a class="f-w-500" href="{{ route('users.show', auth()->user()) }}">
                                <i class="ti ti-eye pe-1 f-s-20"></i> Public Profile
                            </a>
                        </li>
                        <li class="app-divider-v dotted py-1"></li>
                        <li class="dropdown-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none w-100 text-start">
                                    <i class="ti ti-logout pe-1 f-s-20"></i> Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="app-nav" id="app-simple-bar">
            <ul class="main-nav p-0 mt-2">
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#home') }}"></use>
                        </svg>
                        Dashboard
                    </a>
                </li>

                @can('view-organizer-dashboard')
                <li class="{{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('organizer.dashboard') }}">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#chart-pie') }}"></use>
                        </svg>
                        Organizer Analytics
                    </a>
                </li>
                @endcan

                <li class="{{ request()->is('conversations*') ? 'active' : '' }}">
                    <a aria-expanded="{{ request()->is('conversations*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#conversations-menu">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#message-circle') }}"></use>
                        </svg>
                        Conversations
                    </a>
                    <ul class="collapse {{ request()->is('conversations*') ? 'show' : '' }}" id="conversations-menu">
                        <li><a href="{{ route('conversations.index') }}">All Conversations</a></li>
                        <li><a href="{{ route('conversations.create') }}">Create New</a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#bell') }}"></use>
                        </svg>
                        Notifications
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger badge-notification ms-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>

                <li class="{{ request()->is('profile*') ? 'active' : '' }}">
                    <a aria-expanded="{{ request()->is('profile*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#profile-menu">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#user') }}"></use>
                        </svg>
                        Profile
                    </a>
                    <ul class="collapse {{ request()->is('profile*') ? 'show' : '' }}" id="profile-menu">
                        <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
                        <li><a href="{{ route('users.show', auth()->user()) }}">Public Profile</a></li>
                    </ul>
                </li>

                @role('admin')
                <li class="menu-title mt-3">
                    <span>Administration</span>
                </li>
                <li>
                    <a href="/admin" target="_blank">
                        <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#settings') }}"></use>
                        </svg>
                        Admin Panel
                        <i class="ti ti-external-link ms-auto"></i>
                    </a>
                </li>
                @endrole
            </ul>
        </div>
    </nav>
    <!-- Menu Navigation ends -->

    <div class="app-content">
        <!-- Header Section starts -->
        <header class="header-main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                        <span class="header-toggle">
                            <i class="ti ti-menu-2"></i>
                        </span>

                        <div class="header-searchbar w-100">
                            <form action="{{ route('conversations.index') }}" method="GET" class="mx-sm-3 app-form app-icon-form">
                                <div class="position-relative">
                                    <input aria-label="Search" class="form-control" placeholder="Search conversations..." type="search" name="search" value="{{ request('search') }}">
                                    <i class="ti ti-search text-dark"></i>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">
                        <ul class="d-flex align-items-center">
                            <!-- Notifications -->
                            <li class="flex-shrink-0 dropdown notifications">
                                <a aria-expanded="false" class="d-block head-icon" data-bs-toggle="dropdown" href="#">
                                    <i class="ti ti-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge bg-danger rounded-pill position-absolute">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-menu header-card border-0" style="width: 340px;">
                                    <div class="card-header d-flex justify-content-between p-3">
                                        <h5 class="mb-0">Notifications</h5>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0">Mark all read</button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="card-body p-0">
                                        @forelse(auth()->user()->notifications->take(5) as $notification)
                                        <a href="{{ route('notifications.index') }}" class="d-block p-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                                            <div class="d-flex">
                                                <i class="ti ti-bell text-primary me-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 f-s-14">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                                    <p class="mb-0 text-muted f-s-12">{{ Str::limit($notification->data['message'] ?? '', 50) }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                        @empty
                                        <div class="text-center py-4">
                                            <i class="ti ti-bell-off f-s-40 text-muted"></i>
                                            <p class="text-muted mt-2">No notifications</p>
                                        </div>
                                        @endforelse
                                    </div>
                                    @if(auth()->user()->notifications->count() > 5)
                                    <div class="card-footer text-center p-2">
                                        <a href="{{ route('notifications.index') }}" class="btn btn-link btn-sm">View all</a>
                                    </div>
                                    @endif
                                </div>
                            </li>

                            <!-- Profile -->
                            <li class="flex-shrink-0 dropdown">
                                <a aria-expanded="false" class="d-block head-icon" data-bs-toggle="dropdown" href="#">
                                    @if(auth()->user()->avatar)
                                    <img alt="{{ auth()->user()->name }}" class="rounded-circle" src="{{ Storage::url(auth()->user()->avatar) }}" style="width: 35px; height: 35px;">
                                    @else
                                    <span class="avatar-title bg-primary text-white rounded-circle d-inline-block" style="width: 35px; height: 35px; line-height: 35px;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu header-card border-0">
                                    <div class="card-header p-3">
                                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                            <i class="ti ti-user me-2"></i> My Profile
                                        </a>
                                        <a href="{{ route('users.show', auth()->user()) }}" class="dropdown-item">
                                            <i class="ti ti-eye me-2"></i> View Public Profile
                                        </a>
                                        @role('admin')
                                        <a href="/admin" target="_blank" class="dropdown-item">
                                            <i class="ti ti-settings me-2"></i> Admin Panel
                                        </a>
                                        @endrole
                                        <hr class="my-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ti ti-logout me-2"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header Section ends -->

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Page Title -->
                @isset($header)
                <div class="row">
                    <div class="col-12">
                        <div class="page-title mb-4">
                            <h4 class="mb-0">{{ $header }}</h4>
                        </div>
                    </div>
                </div>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('ki-admin/js/jquery.min.js') }}"></script>

<!-- Bootstrap Bundle -->
<script src="{{ asset('ki-admin/vendor/bootstrap.bundle.min.js') }}"></script>

<!-- Vendor JS -->
<script src="{{ asset('ki-admin/vendor/simplebar.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('ki-admin/js/script.js') }}"></script>

@stack('scripts')
</body>
</html>
