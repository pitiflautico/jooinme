<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'JoinMe') }} - @yield('title', 'Login')</title>

    <!-- Favicon -->
    <link href="{{ asset('ki-admin/images/favicon.png') }}" rel="icon" type="image/x-icon">

    <!-- Animation CSS -->
    <link href="{{ asset('ki-admin/vendor/animate.min.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('ki-admin/vendor/tabler-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/vendor/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Main CSS -->
    <link href="{{ asset('ki-admin/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/auth.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card animate__animated animate__fadeIn">
            <div class="auth-logo">
                <a href="/">
                    <img alt="{{ config('app.name') }}" src="{{ asset('images/logo.png') }}">
                </a>
            </div>

            {{ $slot }}

            <div class="auth-footer">
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('ki-admin/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('ki-admin/vendor/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
