@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Buat Akun Baru</h3>
            <p class="text-muted">Gas daftar!</p>
        </div>

        @if($errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('registerakun') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">
                    <i class="fas fa-user me-2"></i>Nama
                </label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Enter your full name"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

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
                       placeholder="Enter your password (min. 6 characters)"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Password must be at least 6 characters long
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Buat Akun
                </button>
            </div>

            <div class="text-center">
                <p class="mb-0">Sudah Punya Akun? 
                    <a href="{{ route('loginruasjalan') }}" class="text-primary fw-semibold text-decoration-none">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection