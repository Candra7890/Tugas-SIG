<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .welcome-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .btn-logout {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            border: none;
            color: white;
        }
        .btn-logout:hover {
            background: linear-gradient(45deg, #ee5a52, #dc3545);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('logoutakun') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card welcome-card mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">
                                    <i class="fas fa-hand-wave me-2"></i>
                                    Welcome back, {{ session('user.name', 'User') }}!
                                </h2>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ session('user.email', 'user@example.com') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="fas fa-user-circle fa-4x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-map fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Ruas Jalan</h5>
                        <p class="card-text text-muted">Lihat peta dan data ruas jalan</p>
                        <a href="{{ route('ruas-jalan') }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i>Lihat Peta
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-cog fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text text-muted">Manage your account preferences</p>
                        <button class="btn btn-outline-success">Configure</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-bell fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text text-muted">Check your latest updates</p>
                        <button class="btn btn-outline-warning">View All</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Session Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Token Status:</strong>
                                <span class="badge bg-success ms-2">
                                    {{ session('token') ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Login Time:</strong>
                                <span class="text-muted ms-2">{{ now()->format('d M Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>