@extends('layouts.app')
@section('title', 'Find a Pet')

@section('content')
<div style="padding-top:76px"></div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">

            {{-- ---- SIDEBAR FILTERS ---- --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius:1.2rem;background:var(--card-bg);position:sticky;top:90px">
                    <div class="card-body p-4">
                        <h5 class="font-display mb-4">Filters</h5>
                        <form action="{{ route('pets.index') }}" method="GET" id="filterForm">

                            {{-- Species --}}
                            <div class="mb-4">
                                <div class="search-label mb-2">Pet Type</div>
                                @foreach(['dog'=>'🐶 Dogs','cat'=>'🐱 Cats','rabbit'=>'🐰 Rabbits','bird'=>'🐦 Birds','other'=>'🐾 Others'] as $val => $label)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="species" value="{{ $val }}" id="sp_{{ $val }}" {{ request('species') === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="sp_{{ $val }}" style="font-size:0.88rem">{{ $label }}</label>
                                </div>
                                @endforeach
                                @if(request('species'))
                                    <a href="{{ route('pets.index') }}" style="font-size:0.78rem;color:var(--terra)">Clear</a>
                                @endif
                            </div>

                            {{-- Size --}}
                            <div class="mb-4">
                                <div class="search-label mb-2">Size</div>
                                @foreach(['small'=>'Small','medium'=>'Medium','large'=>'Large','extra_large'=>'Extra Large'] as $val => $label)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="size" value="{{ $val }}" id="sz_{{ $val }}" {{ request('size') === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="sz_{{ $val }}" style="font-size:0.88rem">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>

                            {{-- Gender --}}
                            <div class="mb-4">
                                <div class="search-label mb-2">Gender</div>
                                @foreach(['male'=>'Male','female'=>'Female'] as $val => $label)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="gender" value="{{ $val }}" id="gd_{{ $val }}" {{ request('gender') === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="gd_{{ $val }}" style="font-size:0.88rem">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>

                            {{-- Age Group --}}
                            <div class="mb-4">
                                <div class="search-label mb-2">Age</div>
                                @foreach(['baby'=>'Baby (0–1 yr)','young'=>'Young (1–3 yrs)','adult'=>'Adult (3–8 yrs)','senior'=>'Senior (8+ yrs)'] as $val => $label)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="age_group" value="{{ $val }}" id="ag_{{ $val }}" {{ request('age_group') === $val ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="ag_{{ $val }}" style="font-size:0.88rem">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>

                            <a href="{{ route('pets.index') }}" class="btn btn-outline-cocoa btn-sm w-100">
                                <i class="bi bi-x-circle me-1"></i>Clear All Filters
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ---- PET LISTING ---- --}}
            <div class="col-lg-9">
                {{-- Top bar --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="font-display mb-0">
                            {{ request('species') ? ucfirst(request('species')).'s' : 'All Pets' }}
                        </h4>
                        <div style="font-size:0.85rem;color:var(--muted)">{{ $pets->total() }} pets found</div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        {{-- Search --}}
                        <form action="{{ route('pets.index') }}" method="GET" class="d-flex">
                            @foreach(request()->except(['search','page']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="Search name or breed" value="{{ request('search') }}" style="border-radius:50px 0 0 50px">
                                <button class="btn btn-terra btn-sm" style="border-radius:0 50px 50px 0"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                        {{-- Sort --}}
                        <form action="{{ route('pets.index') }}" method="GET">
                            @foreach(request()->except(['sort','page']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="border-radius:50px;font-size:0.82rem;min-width:120px">
                                <option value="newest"   {{ request('sort','newest') === 'newest'   ? 'selected' : '' }}>Newest</option>
                                <option value="oldest"   {{ request('sort') === 'oldest'   ? 'selected' : '' }}>Oldest</option>
                                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Grid --}}
                <div class="row g-4">
                    @forelse($pets as $pet)
                    <div class="col-sm-6 col-xl-4">
                        <div class="pet-card h-100" onclick="window.location='{{ route('pets.show', $pet) }}'">
                            <div class="pet-img-wrap">
                                <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}" loading="lazy" />
                                <span class="pet-badge-species">{{ $pet->species_label }}</span>
                                @auth
                                <form action="{{ route('favorites.toggle', $pet) }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="btn-fav">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </form>
                                @endauth
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="pet-name">{{ $pet->name }}</div>
                                    <span class="badge rounded-pill" style="background:var(--cream);color:var(--muted);font-weight:400;font-size:0.72rem">{{ $pet->age_string }}</span>
                                </div>
                                <div class="mb-2" style="font-size:0.82rem;color:var(--muted)">{{ $pet->breed ?? 'Mixed' }} · {{ ucfirst($pet->size) }}</div>
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
                    <div class="col-12 text-center py-5">
                        <div style="font-size:3rem">🐾</div>
                        <h5 class="font-display mt-3">No pets found</h5>
                        <p style="color:var(--muted)">Try adjusting your filters or <a href="{{ route('pets.index') }}">clear all filters</a>.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($pets->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $pets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
