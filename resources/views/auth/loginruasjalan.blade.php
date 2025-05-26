@extends('layouts.app')

@section('title', 'Login Ruas Jalan')

@section('content')
<div class="card">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Selamat Datang Kembali!</h3>
            <p class="text-muted">Silahkan masuk ke akun yang sudah diregistrasi!</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('loginruasjalan') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope me-2"></i>Email
                </label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Enter your password"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </div>

            <div class="text-center">
                <p class="mb-0">Tidak Punya akun? 
                    <a href="{{ route('registerruasjalan') }}" class="text-primary fw-semibold text-decoration-none">
                        Daftar disini!
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection