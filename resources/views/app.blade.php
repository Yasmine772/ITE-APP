<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Navbar with Notifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-info">
    <div class="container">
        <a class="navbar-brand text-white" href="#">ITE App</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>

            </ul>

            <!-- Notification Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ auth()->user()->unreadNotifications->count() }}

            </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 320px;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold"> Notifications ( {{ auth()->user()->unreadNotifications->count() }} )
)</span>
                            <a href="#" class="text-decoration-none text-primary small">Mark all as read</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <!-- Notification 1 -->
                        <li>
                            <a class="dropdown-item d-flex align-items-start" href="#">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="40">
                                <div>
                                    <strong>Aya Ahmad</strong><br>
                                    Uploaded a new file<br>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center text-primary fw-bold" href="#">View All</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
