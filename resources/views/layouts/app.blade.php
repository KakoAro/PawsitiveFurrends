<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pawsitive Furrends') — Find Your Forever Friend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/pawhome.css') }}" rel="stylesheet" />
    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-expand-lg navbar-pawhome fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            🐾 Pawsitive Furrends
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pets.*') ? 'active' : '' }}" href="{{ route('pets.index') }}">Find a Pet</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('community.*') ? 'active' : '' }}" href="{{ route('community.index') }}">Community</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('login') }}"    class="btn btn-outline-cocoa btn-sm px-3">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-terra btn-sm px-3">Sign Up</a>
                @else

                    {{-- NOTIFICATION BELL (admin only) --}}
                    @if(Auth::user()->role === 'admin')
                    @php
                        $pendingAdoptions   = \App\Models\Adoption::where('status','pending')->count();
                        $pendingCommunity   = \App\Models\CommunityPost::where('status','pending')->count();
                        $totalNotifications = $pendingAdoptions + $pendingCommunity;
                    @endphp
                    <div class="dropdown me-1">
                        <button class="btn position-relative" data-bs-toggle="dropdown"
                                style="background:transparent;border:none;padding:6px 10px">
                            <i class="bi bi-bell" style="font-size:1.3rem;color:var(--cocoa-mid)"></i>
                            @if($totalNotifications > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                  style="background:var(--terra);font-size:0.65rem;padding:3px 6px">
                                {{ $totalNotifications }}
                            </span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm p-0"
                             style="width:320px;border-radius:1rem;overflow:hidden;border:0.5px solid var(--tan)">
                            <div class="p-3" style="background:var(--cocoa);color:#fff">
                                <div style="font-weight:600;font-size:0.9rem">🔔 Notifications</div>
                                <div style="font-size:0.75rem;opacity:0.7">{{ $totalNotifications }} item(s) need attention</div>
                            </div>

                            @if($pendingAdoptions > 0)
                            <a href="{{ route('admin.adoptions.index') }}?status=pending"
                               class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                               style="border-bottom:1px solid var(--tan)"
                               onmouseover="this.style.background='var(--cream)'"
                               onmouseout="this.style.background='transparent'">
                                <div style="width:40px;height:40px;border-radius:50%;background:var(--terra-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem">📋</div>
                                <div>
                                    <div style="font-weight:600;font-size:0.85rem;color:var(--cocoa)">Adoption Applications</div>
                                    <div style="font-size:0.78rem;color:var(--muted)">{{ $pendingAdoptions }} pending review</div>
                                </div>
                                <span class="ms-auto badge" style="background:var(--terra-light);color:var(--terra-dark);border-radius:50px">{{ $pendingAdoptions }}</span>
                            </a>
                            @endif

                            @if($pendingCommunity > 0)
                            <a href="{{ route('admin.community.index') }}"
                               class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                               onmouseover="this.style.background='var(--cream)'"
                               onmouseout="this.style.background='transparent'">
                                <div style="width:40px;height:40px;border-radius:50%;background:var(--sage-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem">🐾</div>
                                <div>
                                    <div style="font-weight:600;font-size:0.85rem;color:var(--cocoa)">Community Posts</div>
                                    <div style="font-size:0.78rem;color:var(--muted)">{{ $pendingCommunity }} awaiting approval</div>
                                </div>
                                <span class="ms-auto badge" style="background:var(--sage-light);color:var(--sage);border-radius:50px">{{ $pendingCommunity }}</span>
                            </a>
                            @endif

                            @if($totalNotifications === 0)
                            <div class="p-4 text-center" style="color:var(--muted);font-size:0.85rem">
                                <div style="font-size:2rem;margin-bottom:6px">✅</div>
                                All caught up! No pending items.
                            </div>
                            @endif

                            <div class="p-2" style="background:var(--cream)">
                                <a href="{{ route('admin.pets.index') }}" class="btn btn-terra btn-sm w-100" style="border-radius:50px;font-size:0.82rem">
                                    Go to Admin Panel
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- USER DROPDOWN --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-cocoa btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="bi bi-heart me-2"></i>Favorites</a></li>
                            <li><a class="dropdown-item" href="{{ route('adoptions.mine') }}"><i class="bi bi-file-earmark-text me-2"></i>My Applications</a></li>

                            @if(Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.pets.index') }}" style="color:var(--terra);font-weight:500"><i class="bi bi-shield-check me-2"></i>Admin Panel</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('community.mine') }}"><i class="bi bi-images me-2"></i>My Community Posts</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                    </button>
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
                <div class="font-display fs-4 mb-2" style="color:var(--terra-light)">🐾 Pawsitive Furrends</div>
                <p style="font-size:0.85rem;font-weight:300;max-width:240px;">
                    Connecting loving animals with loving homes. Every pet deserves a family.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Adopt</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('pets.index') }}?species=dog">Find a Dog</a></li>
                    <li><a href="{{ route('pets.index') }}?species=cat">Find a Cat</a></li>
                    <li><a href="{{ route('pets.index') }}">All Animals</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Resources</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('adoption-guide') }}">Adoption Guide</a></li>
                    <li><a href="{{ route('pet-care-tips') }}">Pet Care Tips</a></li>
                    <li><a href="{{ route('shelter-partners') }}">Shelter Partners</a></li>
                    <li><a href="{{ route('success-stories') }}">Success Stories</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Company</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Use</a></li>
                </ul>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1)">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2" style="font-size:0.8rem">
            <span>© {{ date('Y') }} Pawsitive Furrends · Made with ♥ for animals everywhere</span>
            <span>🐾 Every adoption changes two lives</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>
@stack('scripts')
</body>
</html>