@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;background:var(--cream);padding-top:80px">
    <div class="container" style="max-width:480px;padding:2rem 0">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="font-display text-decoration-none" style="font-size:1.8rem;color:var(--terra)">🐾 PawHome</a>
            <h2 class="font-display mt-2 mb-1" style="font-size:1.6rem">Create your account</h2>
            <p style="color:var(--muted);font-size:0.88rem">Join thousands of families finding their forever pet.</p>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
            <div class="card-body p-5">
                @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4" style="font-size:0.88rem">
                    @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-terra w-100 py-3">Create Account 🐾</button>
                </form>

                <p class="text-center mt-4 mb-0" style="font-size:0.88rem;color:var(--muted)">
                    Already have an account? <a href="{{ route('login') }}" style="color:var(--terra);font-weight:500">Log in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
