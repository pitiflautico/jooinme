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
    <link href="{{ asset('ki-admin/vendor/apexcharts.css') }}" rel="stylesheet" type="text/css">

    <!-- Main CSS -->
    <link href="{{ asset('ki-admin/css/variables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('ki-admin/css/custom.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
</head>
<body>
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="main-container">

            <!-- Header -->
            @include('layouts.partials.header')

            <!-- Page Content -->
            <div class="content-wrapper">
                <div class="container-fluid">

                    <!-- Page Header from old layout -->
                    @isset($header)
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between mb-4">
                                <h4 class="mb-0">{{ $header }}</h4>
                            </div>
                        </div>
                    </div>
                    @endisset

                    <!-- Flash Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Main Content -->
                    <main>
                        {{ $slot }}
                    </main>

                </div>
            </div>

            <!-- Footer -->
            @include('layouts.partials.footer')

        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('ki-admin/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('ki-admin/vendor/bootstrap.bundle.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('ki-admin/vendor/simplebar.js') }}"></script>
    <script src="{{ asset('ki-admin/vendor/apexcharts.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('ki-admin/js/script-safe.js') }}"></script>

    @stack('scripts')
</body>
</html>
