<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PawHome') — Find Your Forever Friend</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
    {{-- Custom CSS --}}
    <link href="{{ asset('css/pawhome.css') }}" rel="stylesheet" />

    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-expand-lg navbar-pawhome fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            🐾 PawHome
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pets.*') ? 'active' : '' }}" href="{{ route('pets.index') }}">Find a Pet</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">About</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('login') }}"    class="btn btn-outline-cocoa btn-sm px-3">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-terra btn-sm px-3">Sign Up</a>
                @else
                    <div class="dropdown">
                        <button class="btn btn-outline-cocoa btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="bi bi-heart me-2"></i>Favorites</a></li>
                            <li><a class="dropdown-item" href="{{ route('adoptions.mine') }}"><i class="bi bi-file-earmark-text me-2"></i>My Applications</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-terra" href="{{ route('admin.pets.index') }}"><i class="bi bi-shield-check me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success') || session('error'))
<div class="container mt-5 pt-4">
    @if(session('success'))
        <div class="alert alert-terra alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ===== PAGE CONTENT ===== --}}
@yield('content')

{{-- ===== FOOTER ===== --}}
<footer class="site-footer py-5 mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="font-display fs-4 mb-2" style="color:var(--terra-light)">🐾 PawHome</div>
                <p style="font-size:0.85rem;font-weight:300;max-width:240px;">
                    Connecting loving animals with loving homes since 2018. Every pet deserves a family.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Adopt</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('pets.index') }}?species=dog">Find a Dog</a></li>
                    <li><a href="{{ route('pets.index') }}?species=cat">Find a Cat</a></li>
                    <li><a href="{{ route('pets.index') }}?species=rabbit">Find a Rabbit</a></li>
                    <li><a href="{{ route('pets.index') }}">All Animals</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Resources</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="#">Adoption Guide</a></li>
                    <li><a href="#">Pet Care Tips</a></li>
                    <li><a href="#">Shelter Partners</a></li>
                    <li><a href="#">Success Stories</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Company</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Use</a></li>
                </ul>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1)">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2" style="font-size:0.8rem">
            <span>© {{ date('Y') }} PawHome · Made with ♥ for animals everywhere</span>
            <span>🐾 Every adoption changes two lives</span>
        </div>
    </div>
</footer>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Fade-up animation observer --}}
<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

@stack('scripts')
</body>
</html>
