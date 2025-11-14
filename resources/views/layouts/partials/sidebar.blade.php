<aside class="sidebar" data-simplebar>
    <div class="sidebar-wrapper">

        <!-- Logo -->
        <div class="logo-wrapper">
            <a class="logo d-inline-block" href="{{ route('dashboard') }}">
                <img alt="{{ config('app.name') }}" src="{{ asset('images/logo.svg') }}" style="height: 40px;">
            </a>
            <button class="sidebar-toggle btn btn-icon btn-sm" type="button">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <ul class="sidebar-menu" id="sidebar-menu">

                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#home') }}"></use>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                @can('view-organizer-dashboard')
                <!-- Organizer Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}" href="{{ route('organizer.dashboard') }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#chart-pie') }}"></use>
                        </svg>
                        <span>Organizer Analytics</span>
                    </a>
                </li>
                @endcan

                <!-- Conversations -->
                <li class="sidebar-item {{ request()->is('conversations*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow" data-bs-toggle="collapse" href="#conversations-menu" role="button" aria-expanded="{{ request()->is('conversations*') ? 'true' : 'false' }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#message-circle') }}"></use>
                        </svg>
                        <span>Conversations</span>
                        <i class="ti ti-chevron-right ms-auto"></i>
                    </a>
                    <ul class="sidebar-submenu collapse {{ request()->is('conversations*') ? 'show' : '' }}" id="conversations-menu">
                        <li><a href="{{ route('conversations.index') }}" class="{{ request()->routeIs('conversations.index') ? 'active' : '' }}">All Conversations</a></li>
                        <li><a href="{{ route('conversations.create') }}" class="{{ request()->routeIs('conversations.create') ? 'active' : '' }}">Create New</a></li>
                    </ul>
                </li>

                <!-- Calendar -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('calendar') ? 'active' : '' }}" href="{{ route('conversations.index') }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#calendar') }}"></use>
                        </svg>
                        <span>Calendar</span>
                    </a>
                </li>

                <!-- Notifications -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#bell') }}"></use>
                        </svg>
                        <span>Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>

                <!-- Profile -->
                <li class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow" data-bs-toggle="collapse" href="#profile-menu" role="button" aria-expanded="{{ request()->is('profile*') ? 'true' : 'false' }}">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#user') }}"></use>
                        </svg>
                        <span>Profile</span>
                        <i class="ti ti-chevron-right ms-auto"></i>
                    </a>
                    <ul class="sidebar-submenu collapse {{ request()->is('profile*') ? 'show' : '' }}" id="profile-menu">
                        <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">My Profile</a></li>
                        <li><a href="{{ route('users.show', auth()->user()) }}" class="{{ request()->routeIs('users.show') ? 'active' : '' }}">Public Profile</a></li>
                    </ul>
                </li>

                @role('admin')
                <!-- Admin Section -->
                <li class="sidebar-header">
                    <span>Administration</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/admin" target="_blank">
                        <svg class="icon">
                            <use xlink:href="{{ asset('ki-admin/svg/_sprite.svg#settings') }}"></use>
                        </svg>
                        <span>Admin Panel</span>
                        <i class="ti ti-external-link ms-auto"></i>
                    </a>
                </li>
                @endrole

            </ul>
        </nav>

    </div>
</aside>
