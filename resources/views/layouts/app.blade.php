<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-info shadow-sm">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/') }}">Home</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle text-white position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 320px;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center px-3">
                                <span class="fw-bold">Notifications (3)</span>
                                <a href="#" class="text-decoration-none text-primary small">Mark all as read</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start" href="#">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="40" alt="User Image">
                                    <div>
                                        <strong>Aya Ahmad</strong><br>
                                        Uploaded a new file<br>
                                        <small class="text-muted">Just now</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start" href="#">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="40" alt="User Image">
                                    <div>
                                        <strong>Mohamad Ali</strong><br>
                                        Sent you a message<br>
                                        <small class="text-muted">5 mins ago</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-start" href="#">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="40" alt="User Image">
                                    <div>
                                        <strong>Lina Hasan</strong><br>
                                        Commented on your post<br>
                                        <small class="text-muted">10 mins ago</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center text-primary fw-bold" href="#">View All</a></li>
                        </ul>
                    </li>


                    <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS Bundle (Popper + Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
