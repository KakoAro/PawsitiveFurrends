{{-- ================================================================
     resources/views/auth/login.blade.php
     ================================================================ --}}
@extends('layouts.app')
@section('title', 'Log In')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;background:var(--cream);padding-top:80px">
    <div class="container" style="max-width:440px">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="font-display text-decoration-none" style="font-size:1.8rem;color:var(--terra)">🐾 PawHome</a>
            <h2 class="font-display mt-2 mb-1" style="font-size:1.6rem">Welcome back</h2>
            <p style="color:var(--muted);font-size:0.88rem">Log in to manage your adoption applications.</p>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
            <div class="card-body p-5">
                @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4" style="font-size:0.88rem">
                    @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember" style="font-size:0.85rem">Remember me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-terra w-100 py-3">Log In</button>
                </form>

                <p class="text-center mt-4 mb-0" style="font-size:0.88rem;color:var(--muted)">
                    Don't have an account? <a href="{{ route('register') }}" style="color:var(--terra);font-weight:500">Sign up free</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
