@extends('layouts.blank')
@section('title', 'Login — Pawsitive Furrends')

@section('content')
<div style="min-height:100vh;display:flex">

    {{-- LEFT SIDE --}}
    <div class="d-none d-lg-flex flex-column justify-content-between p-5"
         style="width:50%;background:var(--cocoa);position:relative;overflow:hidden">

        <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=900&q=80') center/cover;opacity:0.2"></div>
        <div style="position:absolute;inset:0;background:linear-gradient(160deg,rgba(74,55,40,0.95) 0%,rgba(156,80,50,0.88) 100%)"></div>

        {{-- Logo --}}
        <div style="position:relative">
            <a href="{{ route('home') }}" style="text-decoration:none">
                <div class="font-display" style="color:#fff;font-size:1.5rem;letter-spacing:-0.3px">Pawsitive Furrends</div>
            </a>
        </div>

        {{-- Center content --}}
        <div style="position:relative;text-align:center">
            <h1 class="font-display" style="color:#fff;font-size:2.6rem;line-height:1.2;margin-bottom:1rem">
                Every pet deserves a <em style="color:var(--terra-light)">forever</em> home
            </h1>
            <p style="color:rgba(255,255,255,0.7);font-size:0.95rem;font-weight:300;line-height:1.8;margin-bottom:2.5rem;max-width:400px;margin-inline:auto">
                Join our community and help connect animals with loving families across the Philippines.
            </p>

            {{-- Access type selector --}}
            <div style="margin-bottom:1.2rem">
                <div style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,0.5);margin-bottom:1rem">
                    Continue as
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button onclick="setRole('user')" id="btn-user" class="login-role-btn active">
                        <div style="font-weight:600;font-size:0.95rem;margin-bottom:3px">Guest</div>
                        <div style="font-size:0.75rem;opacity:0.75">Browse and adopt pets</div>
                    </button>
                    <button onclick="setRole('admin')" id="btn-admin" class="login-role-btn">
                        <div style="font-weight:600;font-size:0.95rem;margin-bottom:3px">Administrator</div>
                        <div style="font-size:0.75rem;opacity:0.75">Manage shelter and pets</div>
                    </button>
                </div>
            </div>

            {{-- Stats --}}
            <div class="d-flex gap-4 justify-content-center mt-4">
                @foreach(['5+ Pets Available','3 Shelter Partners','100+ Happy Adoptions'] as $stat)
                <div style="font-size:0.78rem;color:rgba(255,255,255,0.5)">&#10003; {{ $stat }}</div>
                @endforeach
            </div>
        </div>

        <div style="position:relative;font-size:0.75rem;color:rgba(255,255,255,0.35);text-align:center">
            &copy; {{ date('Y') }} Pawsitive Furrends &middot; Made with love for animals everywhere
        </div>
    </div>

    {{-- RIGHT SIDE — Form --}}
    <div class="d-flex flex-column justify-content-center align-items-center p-5 flex-fill"
         style="background:var(--cream)">
        <div style="width:100%;max-width:420px">

            <div class="d-lg-none text-center mb-4">
                <div class="font-display" style="font-size:1.5rem;color:var(--terra)">Pawsitive Furrends</div>
            </div>

            <div id="role-badge" class="mb-3" style="display:inline-flex;align-items:center;gap:8px;background:var(--terra-light);border-radius:50px;padding:5px 14px">
                <span id="role-icon" style="font-size:0.75rem;color:var(--terra-dark)">&#9679;</span>
                <span id="role-text" style="font-size:0.82rem;font-weight:500;color:var(--terra-dark)">Signing in as Guest</span>
            </div>

            <h2 class="font-display mb-1" style="font-size:1.9rem">Welcome back!</h2>
            <p style="color:var(--muted);font-size:0.9rem;margin-bottom:2rem">Sign in to continue to your account.</p>

            @if($errors->any())
            <div class="mb-4 p-3 rounded-3" style="background:var(--terra-light);border:1px solid var(--terra);color:var(--terra-dark);font-size:0.88rem">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="login_role" id="login_role" value="user">

                <div class="mb-3">
                    <label class="form-label" style="font-weight:500;font-size:0.88rem">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}"
                           placeholder="you@example.com" autofocus required
                           style="padding:0.8rem 1rem;border-radius:0.7rem">
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:500;font-size:0.88rem">Password</label>
                    <input type="password" name="password" class="form-control" required
                           placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                           style="padding:0.8rem 1rem;border-radius:0.7rem">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-terra w-100 py-3 mb-3" style="border-radius:50px;font-size:1rem;font-weight:600">
                    Sign In &rarr;
                </button>
            </form>

            <div class="text-center mb-2" style="font-size:0.85rem;color:var(--muted)">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:var(--terra);font-weight:600">Sign up free</a>
            </div>
            <div class="text-center">
                <a href="{{ route('home') }}" style="font-size:0.82rem;color:var(--muted)">
                    &larr; Back to homepage
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.login-role-btn {
    padding: 1rem 1.5rem;
    border-radius: 0.9rem;
    border: 1.5px solid rgba(255,255,255,0.15);
    background: transparent;
    color: rgba(255,255,255,0.65);
    cursor: pointer;
    text-align: center;
    min-width: 160px;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.login-role-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.08);
    opacity: 0;
    transition: opacity 0.25s;
}
.login-role-btn:hover::before { opacity: 1; }
.login-role-btn:hover {
    border-color: rgba(255,255,255,0.4);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.login-role-btn.active {
    background: rgba(255,255,255,0.18) !important;
    border-color: rgba(255,255,255,0.6) !important;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
</style>

<script>
function setRole(role) {
    document.getElementById('login_role').value = role;
    const isAdmin = role === 'admin';
    document.getElementById('btn-user').classList.toggle('active', !isAdmin);
    document.getElementById('btn-admin').classList.toggle('active', isAdmin);
    document.getElementById('role-text').textContent = isAdmin ? 'Signing in as Administrator' : 'Signing in as Guest';
}
</script>
@endsection