@extends('layouts.blank')
@section('title', 'Login — Pawsitive Furrends')

@section('content')
<div style="min-height:100vh;display:flex">

    {{-- LEFT SIDE — Theme Panel --}}
    <div class="d-none d-lg-flex flex-column justify-content-between p-5"
         style="width:50%;background:var(--cocoa);position:relative;overflow:hidden">

        {{-- Background image overlay --}}
        <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=900&q=80') center/cover;opacity:0.25"></div>
        <div style="position:absolute;inset:0;background:linear-gradient(160deg,rgba(74,55,40,0.95) 0%,rgba(156,80,50,0.85) 100%)"></div>

        {{-- Logo --}}
        <div style="position:relative">
            <a href="{{ route('home') }}" style="text-decoration:none">
                <div class="font-display" style="color:#fff;font-size:1.6rem">🐾 Pawsitive Furrends</div>
            </a>
        </div>

        {{-- Center content --}}
        <div style="position:relative">
            <h1 class="font-display" style="color:#fff;font-size:2.8rem;line-height:1.2;margin-bottom:1.2rem">
                Every pet deserves a <em style="color:var(--terra-light)">forever</em> home
            </h1>
            <p style="color:rgba(255,255,255,0.75);font-size:1rem;font-weight:300;line-height:1.8;margin-bottom:2.5rem">
                Join our community of pet lovers and help connect animals with loving families across the Philippines.
            </p>

            {{-- Access type selector --}}
            <div style="margin-bottom:1.5rem">
                <div style="font-size:0.8rem;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:rgba(255,255,255,0.6);margin-bottom:0.8rem">
                    Continue as
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <button onclick="setRole('user')" id="btn-user"
                            class="role-btn active"
                            style="flex:1;padding:1rem 1.2rem;border-radius:1rem;border:1.5px solid rgba(255,255,255,0.3);background:rgba(255,255,255,0.15);color:#fff;cursor:pointer;text-align:left;backdrop-filter:blur(8px);transition:all 0.2s">
                        <div style="font-size:1.5rem;margin-bottom:4px">🐾</div>
                        <div style="font-weight:600;font-size:0.9rem">Guest / Adopter</div>
                        <div style="font-size:0.75rem;opacity:0.75;margin-top:2px">Browse & adopt pets</div>
                    </button>
                    <button onclick="setRole('admin')" id="btn-admin"
                            style="flex:1;padding:1rem 1.2rem;border-radius:1rem;border:1.5px solid rgba(255,255,255,0.15);background:transparent;color:rgba(255,255,255,0.7);cursor:pointer;text-align:left;transition:all 0.2s">
                        <div style="font-size:1.5rem;margin-bottom:4px">🛡️</div>
                        <div style="font-weight:600;font-size:0.9rem">Administrator</div>
                        <div style="font-size:0.75rem;opacity:0.75;margin-top:2px">Manage shelter & pets</div>
                    </button>
                </div>
            </div>

            {{-- Stats --}}
            <div class="d-flex gap-4">
                @foreach(['5+ Pets Available','3 Shelter Partners','100+ Happy Adoptions'] as $stat)
                <div style="font-size:0.82rem;color:rgba(255,255,255,0.6)">✓ {{ $stat }}</div>
                @endforeach
            </div>
        </div>

        {{-- Bottom --}}
        <div style="position:relative;font-size:0.78rem;color:rgba(255,255,255,0.4)">
            © {{ date('Y') }} Pawsitive Furrends · Made with ♥ for animals everywhere
        </div>
    </div>

    {{-- RIGHT SIDE — Login Form --}}
    <div class="d-flex flex-column justify-content-center align-items-center p-5 flex-fill"
         style="background:var(--cream)">
        <div style="width:100%;max-width:420px">

            {{-- Mobile logo --}}
            <div class="d-lg-none text-center mb-4">
                <a href="{{ route('home') }}" class="font-display text-decoration-none" style="font-size:1.6rem;color:var(--terra)">🐾 Pawsitive Furrends</a>
            </div>

            {{-- Role badge --}}
            <div id="role-badge" class="mb-3 d-flex align-items-center gap-2"
                 style="background:var(--terra-light);border-radius:50px;padding:6px 14px;display:inline-flex;width:fit-content">
                <span id="role-icon">🐾</span>
                <span id="role-text" style="font-size:0.82rem;font-weight:500;color:var(--terra-dark)">Signing in as Guest / Adopter</span>
            </div>

            <h2 class="font-display mb-1" style="font-size:1.9rem">Welcome back!</h2>
            <p style="color:var(--muted);font-size:0.9rem;margin-bottom:2rem">Sign in to continue to your account.</p>

            @if($errors->any())
            <div class="alert rounded-3 mb-4" style="background:var(--terra-light);border:1px solid var(--terra);color:var(--terra-dark);font-size:0.88rem">
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
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0" style="font-weight:500;font-size:0.88rem">Password</label>
                    </div>
                    <input type="password" name="password" class="form-control" required
                           placeholder="••••••••"
                           style="padding:0.8rem 1rem;border-radius:0.7rem">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-terra w-100 py-3 mb-3" style="border-radius:50px;font-size:1rem;font-weight:600">
                    Sign In <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mb-3" style="font-size:0.85rem;color:var(--muted)">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:var(--terra);font-weight:600">Sign up free</a>
            </div>

            <div class="text-center">
                <a href="{{ route('home') }}" style="font-size:0.82rem;color:var(--muted)">
                    <i class="bi bi-arrow-left me-1"></i>Back to homepage
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.role-btn.active {
    background: rgba(255,255,255,0.25) !important;
    border-color: rgba(255,255,255,0.7) !important;
    color: #fff !important;
}
</style>

<script>
function setRole(role) {
    document.getElementById('login_role').value = role;
    const isAdmin = role === 'admin';

    document.getElementById('btn-user').className = 'role-btn' + (!isAdmin ? ' active' : '');
    document.getElementById('btn-admin').className = 'role-btn' + (isAdmin ? ' active' : '');
    document.getElementById('btn-user').style.background = !isAdmin ? 'rgba(255,255,255,0.25)' : 'transparent';
    document.getElementById('btn-admin').style.background = isAdmin ? 'rgba(255,255,255,0.25)' : 'transparent';
    document.getElementById('btn-user').style.borderColor = !isAdmin ? 'rgba(255,255,255,0.7)' : 'rgba(255,255,255,0.15)';
    document.getElementById('btn-admin').style.borderColor = isAdmin ? 'rgba(255,255,255,0.7)' : 'rgba(255,255,255,0.15)';

    document.getElementById('role-icon').textContent = isAdmin ? '🛡️' : '🐾';
    document.getElementById('role-text').textContent = isAdmin ? 'Signing in as Administrator' : 'Signing in as Guest / Adopter';
}
</script>
@endsection