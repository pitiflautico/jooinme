<header class="header-main">
    <div class="header-wrapper">
        <div class="header-left">
            <!-- Mobile Menu Toggle -->
            <button class="sidebar-toggle btn btn-icon btn-sm d-lg-none" type="button">
                <i class="ti ti-menu-2"></i>
            </button>

            <!-- Search Bar -->
            <div class="search-wrapper d-none d-md-flex">
                <form action="{{ route('conversations.index') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search conversations..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="header-right">
            <!-- Theme Toggle -->
            <div class="header-item">
                <button class="btn btn-icon btn-sm theme-toggle" type="button" id="theme-toggle">
                    <i class="ti ti-sun" id="theme-icon-light"></i>
                    <i class="ti ti-moon d-none" id="theme-icon-dark"></i>
                </button>
            </div>

            <!-- Notifications Dropdown -->
            <div class="header-item dropdown">
                <button class="btn btn-icon btn-sm dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger badge-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 320px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">Mark all read</button>
                        </form>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider"></li>

                    @forelse(auth()->user()->notifications->take(5) as $notification)
                    <li>
                        <a class="dropdown-item {{ $notification->read_at ? '' : 'unread' }}" href="{{ route('notifications.index') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ti ti-bell"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                    <p class="mb-0 text-muted small">{{ Str::limit($notification->data['message'] ?? '', 50) }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li>
                        <div class="dropdown-item text-center text-muted py-4">
                            <i class="ti ti-bell-off mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">No notifications</p>
                        </div>
                    </li>
                    @endforelse

                    @if(auth()->user()->notifications->count() > 5)
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center text-primary" href="{{ route('notifications.index') }}">
                            View all notifications
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- User Profile Dropdown -->
            <div class="header-item dropdown">
                <button class="btn btn-icon dropdown-toggle user-dropdown" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(auth()->user()->avatar)
                    <img alt="{{ auth()->user()->name }}" class="rounded-circle" src="{{ Storage::url(auth()->user()->avatar) }}" width="36" height="36">
                    @else
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary text-white rounded-circle">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="userDropdown">
                    <li class="dropdown-header">
                        <div class="d-flex align-items-center">
                            @if(auth()->user()->avatar)
                            <img alt="{{ auth()->user()->name }}" class="rounded-circle me-2" src="{{ Storage::url(auth()->user()->avatar) }}" width="40" height="40">
                            @else
                            <div class="avatar-sm me-2">
                                <span class="avatar-title bg-primary text-white rounded-circle">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="ti ti-user me-2"></i>
                            My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', auth()->user()) }}">
                            <i class="ti ti-eye me-2"></i>
                            View Public Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('conversations.index') }}?filter=my">
                            <i class="ti ti-message-circle me-2"></i>
                            My Conversations
                        </a>
                    </li>
                    @role('admin')
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/admin" target="_blank">
                            <i class="ti ti-settings me-2"></i>
                            Admin Panel
                        </a>
                    </li>
                    @endrole
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-logout me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const iconLight = document.getElementById('theme-icon-light');
    const iconDark = document.getElementById('theme-icon-dark');

    // Check for saved theme preference or default to 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme === 'dark') {
        iconLight.classList.add('d-none');
        iconDark.classList.remove('d-none');
    }

    themeToggle.addEventListener('click', function() {
        let theme = document.documentElement.getAttribute('data-theme');

        if (theme === 'light') {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            iconLight.classList.add('d-none');
            iconDark.classList.remove('d-none');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            iconLight.classList.remove('d-none');
            iconDark.classList.add('d-none');
        }
    });
});
</script>
@endpush
