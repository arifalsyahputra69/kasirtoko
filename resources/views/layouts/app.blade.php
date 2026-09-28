<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kasir Toko - Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
            transition: 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #e9ecef;
            font-weight: 500;
        }

        .sidebar i {
            margin-right: 8px;
        }

        .content-area {
            padding: 25px;
        }
    </style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container-fluid">

        <span class="navbar-brand">
            <i class="fas fa-cash-register me-2"></i>Kasir Toko
        </span>

        <div class="d-flex align-items-center text-white">
            <span class="me-3">
                <i class="fas fa-user-circle me-1"></i>
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>

    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- ================= SIDEBAR ================= -->
        <div class="col-md-2 sidebar">

            <!-- Dashboard Karyawan -->
            <a href="{{ route('karyawan.dashboard') }}"
               class="{{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>

        </div>

        <!-- ================= CONTENT ================= -->
        <div class="col-md-10 content-area">
            @yield('content')
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')

</body>
</html>
