@extends('layouts.app')
@section('title', 'PawHome')

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <span style="width:6px;height:6px;background:var(--terra);border-radius:50%;display:inline-block"></span>
                    3,200+ Happy Adoptions This Year
                </div>
                <h1 class="display-4 fw-bold mb-3" style="color:var(--cocoa);line-height:1.15">
                    Give a pet their <em class="text-terra">forever</em> home today
                </h1>
                <p class="mb-4" style="font-size:1.05rem;color:var(--muted);font-weight:300;max-width:460px;">
                    Thousands of loving animals are waiting for someone just like you. Find your perfect companion and change two lives at once.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('pets.index') }}" class="btn btn-terra px-4 py-2">
                        🐶 Find Your Pet
                    </a>
                    <a href="#how" class="btn btn-outline-cocoa px-4 py-2">
                        Learn How It Works
                    </a>
                </div>

                {{-- Stats --}}
                <div class="d-flex gap-4 mt-4 pt-4" style="border-top:1px solid var(--tan)">
                    <div>
                        <div class="font-display fw-bold" style="font-size:2rem;color:var(--cocoa)">{{ number_format($totalPets) }}+</div>
                        <div style="font-size:0.8rem;color:var(--muted)">Pets Available</div>
                    </div>
                    <div>
                        <div class="font-display fw-bold" style="font-size:2rem;color:var(--cocoa)">98%</div>
                        <div style="font-size:0.8rem;color:var(--muted)">Match Success Rate</div>
                    </div>
                    <div>
                        <div class="font-display fw-bold" style="font-size:2rem;color:var(--cocoa)">{{ $totalShelters }}+</div>
                        <div style="font-size:0.8rem;color:var(--muted)">Partner Shelters</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="position-relative" style="max-width:420px;margin:auto">
                    <div style="border-radius:40% 60% 60% 40%/50% 40% 60% 50%;overflow:hidden;aspect-ratio:4/5;background:var(--terra-light)">
                        <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&q=80"
                             alt="Happy dog" style="width:100%;height:100%;object-fit:cover">
                    </div>
                    {{-- Floating card --}}
                    <div class="card border-0 shadow-sm position-absolute" style="top:10%;right:-5%;border-radius:0.9rem;padding:0.7rem 1rem;background:var(--card-bg)">
                        <div class="d-flex align-items-center gap-2" style="font-size:0.82rem">
                            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=80&q=80"
                                 alt="cat" style="width:38px;height:38px;border-radius:50%;object-fit:cover">
                            <div>
                                <div style="font-weight:500;color:var(--cocoa)">Luna</div>
                                <div style="color:var(--muted);font-size:0.75rem">Tabby Cat · 2 yrs</div>
                                <span style="font-size:0.72rem;background:var(--sage-light);color:var(--sage);padding:2px 8px;border-radius:50px">✓ Just Adopted!</span>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm position-absolute" style="bottom:12%;left:-5%;border-radius:0.9rem;padding:0.7rem 1rem;background:var(--card-bg)">
                        <div class="font-display fw-bold text-terra" style="font-size:1.4rem">{{ $totalPets }}</div>
                        <div style="font-size:0.78rem;color:var(--muted)">pets near you</div>
                        <div style="font-size:0.72rem;color:var(--terra)">waiting for a home ♥</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== SEARCH BAR ===== --}}
<section class="py-3">
    <div class="container">
        <div class="search-card">
            <form action="{{ route('pets.index') }}" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                <div class="flex-fill" style="min-width:140px">
                    <div class="search-label">Pet Type</div>
                    <select name="species" class="search-input">
                        <option value="">All Animals</option>
                        <option value="dog">Dogs</option>
                        <option value="cat">Cats</option>
                        <option value="rabbit">Rabbits</option>
                        <option value="bird">Birds</option>
                        <option value="other">Others</option>
                    </select>
                </div>
                <div class="search-divider d-none d-md-block"></div>
                <div class="flex-fill" style="min-width:140px">
                    <div class="search-label">Breed / Name</div>
                    <input name="search" class="search-input" placeholder="Any breed or name" />
                </div>
                <div class="search-divider d-none d-md-block"></div>
                <div class="flex-fill" style="min-width:120px">
                    <div class="search-label">Age</div>
                    <select name="age_group" class="search-input">
                        <option value="">Any Age</option>
                        <option value="baby">Baby (0–1 yr)</option>
                        <option value="young">Young (1–3 yrs)</option>
                        <option value="adult">Adult (3–8 yrs)</option>
                        <option value="senior">Senior (8+ yrs)</option>
                    </select>
                </div>
                <div class="search-divider d-none d-md-block"></div>
                <div class="flex-fill" style="min-width:100px">
                    <div class="search-label">Size</div>
                    <select name="size" class="search-input">
                        <option value="">Any Size</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-terra px-4">Search Pets</button>
            </form>
        </div>
    </div>
</section>

{{-- ===== FEATURED PETS ===== --}}
<section class="py-5" id="pets">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-tag">Meet Our Pets</div>
            <h2 class="font-display mb-2">Animals looking for <em class="text-terra">love</em></h2>
            <p style="color:var(--muted);font-weight:300;max-width:480px;margin:auto">Every one of these companions has been health-checked, vaccinated, and is ready to meet you.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap justify-content-center mb-4">
            <a href="{{ route('pets.index') }}"                         class="filter-tab {{ !request('species') ? 'active' : '' }}">All</a>
            <a href="{{ route('pets.index') }}?species=dog"             class="filter-tab {{ request('species') === 'dog'    ? 'active' : '' }}">🐶 Dogs</a>
            <a href="{{ route('pets.index') }}?species=cat"             class="filter-tab {{ request('species') === 'cat'    ? 'active' : '' }}">🐱 Cats</a>
            <a href="{{ route('pets.index') }}?species=rabbit"          class="filter-tab {{ request('species') === 'rabbit' ? 'active' : '' }}">🐰 Rabbits</a>
            <a href="{{ route('pets.index') }}?species=bird"            class="filter-tab {{ request('species') === 'bird'   ? 'active' : '' }}">🐦 Birds</a>
            <a href="{{ route('pets.index') }}?species=other"           class="filter-tab {{ request('species') === 'other'  ? 'active' : '' }}">🐾 Others</a>
        </div>

        <div class="row g-4">
            @forelse($featuredPets as $pet)
            <div class="col-sm-6 col-lg-4 fade-up">
                <div class="pet-card h-100" onclick="window.location='{{ route('pets.show', $pet) }}'">
                    <div class="pet-img-wrap">
                        <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}" loading="lazy" />
                        <span class="pet-badge-species">{{ $pet->species_label }}</span>
                        @auth
                        <form action="{{ route('favorites.toggle', $pet) }}" method="POST" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="btn-fav">
                                <i class="bi bi-heart{{ $pet->favoritedBy()->where('user_id', auth()->id())->exists() ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        @endauth
                    </div>
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="pet-name">{{ $pet->name }}</div>
                            <span class="badge rounded-pill" style="background:var(--cream);color:var(--muted);font-weight:400;font-size:0.75rem">{{ $pet->age_string }}</span>
                        </div>
                        <div class="mb-2" style="font-size:0.82rem;color:var(--muted)">{{ $pet->breed }} · {{ ucfirst($pet->size) }}</div>
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($pet->tags->take(3) as $tag)
                                <span class="pet-tag">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:0.78rem;color:var(--muted)">📍 {{ $pet->shelter->city }}</span>
                            <a href="{{ route('pets.show', $pet) }}" class="btn btn-terra btn-sm px-3" onclick="event.stopPropagation()">Adopt</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12 text-center py-5" style="color:var(--muted)">
                    No featured pets available right now. <a href="{{ route('pets.index') }}">Browse all pets</a>.
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('pets.index') }}" class="btn btn-outline-terra px-5 py-2">
                View All Pets <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-5 bg-warm-white" id="how">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-tag">Simple Process</div>
            <h2 class="font-display mb-2">How adoption <em class="text-terra">works</em></h2>
            <p style="color:var(--muted);font-weight:300;max-width:480px;margin:auto">We've made it easy to find, meet, and bring home your new best friend in just a few steps.</p>
        </div>
        <div class="row g-4 text-center">
            @foreach([
                ['icon'=>'🔍','title'=>'Browse & Search',     'desc'=>'Filter by species, age, size, and breed to find pets that match your lifestyle perfectly.','bg'=>'var(--terra-light)'],
                ['icon'=>'💬','title'=>'Connect with Shelter', 'desc'=>'Reach out to the shelter directly to ask questions and arrange a meet-and-greet visit.',   'bg'=>'var(--sage-light)'],
                ['icon'=>'📋','title'=>'Apply to Adopt',       'desc'=>'Fill out a simple adoption application. Our team reviews it within 24 hours.',              'bg'=>'var(--terra-light)'],
                ['icon'=>'🏠','title'=>'Welcome Home!',        'desc'=>'Once approved, bring your new companion home and start building a beautiful bond.',          'bg'=>'var(--sage-light)'],
            ] as $i => $step)
            <div class="col-6 col-lg-3 fade-up">
                <div class="step-icon mx-auto mb-3" style="background:{{ $step['bg'] }}">
                    <span>{{ $step['icon'] }}</span>
                    <div class="step-num">{{ $i+1 }}</div>
                </div>
                <h5 class="font-display mb-2">{{ $step['title'] }}</h5>
                <p style="font-size:0.88rem;color:var(--muted);font-weight:300">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
<section class="py-5" id="categories">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-tag">Browse by Type</div>
            <h2 class="font-display">Find your <em class="text-terra">perfect</em> companion</h2>
        </div>
        <div class="row g-3 fade-up">
            @foreach([
                ['emoji'=>'🐶','name'=>'Dogs',    'species'=>'dog'],
                ['emoji'=>'🐱','name'=>'Cats',    'species'=>'cat'],
                ['emoji'=>'🐰','name'=>'Rabbits', 'species'=>'rabbit'],
                ['emoji'=>'🐦','name'=>'Birds',   'species'=>'bird'],
                ['emoji'=>'🐾','name'=>'Others',  'species'=>'other'],
            ] as $cat)
            <div class="col-6 col-sm-4 col-lg">
                <a href="{{ route('pets.index') }}?species={{ $cat['species'] }}" class="cat-card {{ $cat['species'] === 'dog' ? 'featured' : '' }}">
                    <div class="cat-emoji">{{ $cat['emoji'] }}</div>
                    <div class="cat-name">{{ $cat['name'] }}</div>
                    <div class="cat-count">{{ number_format($speciesCounts[$cat['species']] ?? 0) }} available</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA BANNER ===== --}}
<section class="py-5" id="about">
    <div class="container">
        <div class="cta-banner p-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7" style="position:relative">
                    <h2 class="font-display text-white mb-2">Ready to find your <em style="color:var(--terra-light)">forever friend?</em></h2>
                    <p style="color:var(--cocoa-light);font-weight:300;max-width:440px">
                        Join thousands of families who've found their perfect companion through PawHome. Every adoption saves a life.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end d-flex gap-3 justify-content-lg-end flex-wrap" style="position:relative">
                    <a href="{{ route('pets.index') }}" class="btn btn-white px-4 py-2">Browse Pets 🐾</a>
                    @guest
                    <a href="{{ route('register') }}"  class="btn btn-ghost px-4 py-2" style="color:var(--cocoa-light);border:1.5px solid rgba(255,255,255,0.2);border-radius:50px">Sign Up Free</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
