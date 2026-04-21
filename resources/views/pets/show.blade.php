@extends('layouts.app')
@section('title', $pet->name . ' — ' . $pet->breed)

@section('content')
<div style="padding-top:76px"></div>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--terra)">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pets.index') }}" style="color:var(--terra)">Pets</a></li>
                <li class="breadcrumb-item active" style="color:var(--muted)">{{ $pet->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            {{-- ---- LEFT: Images ---- --}}
            <div class="col-lg-6">
                <div style="border-radius:1.5rem;overflow:hidden;aspect-ratio:1;background:var(--terra-light)">
                    <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}" style="width:100%;height:100%;object-fit:cover" id="mainImage">
                </div>
                @if($pet->images->count())
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach($pet->images as $img)
                    <div style="width:70px;height:70px;border-radius:0.6rem;overflow:hidden;cursor:pointer;border:2px solid transparent"
                         onclick="document.getElementById('mainImage').src='{{ asset('storage/'.$img->image_path) }}'">
                        <img src="{{ asset('storage/'.$img->image_path) }}" style="width:100%;height:100%;object-fit:cover" alt="{{ $img->caption }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ---- RIGHT: Info ---- --}}
            <div class="col-lg-6">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                    <span style="background:var(--terra-light);color:var(--terra-dark);font-size:0.78rem;font-weight:500;padding:4px 12px;border-radius:50px">
                        {{ $pet->species_label }}
                    </span>
                    <div class="d-flex gap-2">
                        @if($pet->featured)
                        <span class="badge" style="background:var(--sage-light);color:var(--sage)">⭐ Featured</span>
                        @endif
                    </div>
                </div>

                <h1 class="font-display mb-1" style="font-size:2.5rem;color:var(--cocoa)">{{ $pet->name }}</h1>
                <p style="color:var(--muted);font-size:1rem">{{ $pet->breed ?? 'Mixed Breed' }} · {{ $pet->age_string }} · {{ ucfirst($pet->size) }} · {{ ucfirst($pet->gender) }}</p>

                <div class="d-flex flex-wrap gap-2 my-3">
                    @foreach($pet->tags as $tag)
                        <span class="pet-tag">{{ $tag->name }}</span>
                    @endforeach
                </div>

                <p style="color:var(--muted);font-weight:300;line-height:1.8;font-size:0.95rem">{{ $pet->description }}</p>

                {{-- Health Details --}}
                <div class="row g-3 my-4">
                    @foreach([
                        ['label'=>'Vaccinated',   'val'=>$pet->is_vaccinated,   'icon'=>'bi-shield-check'],
                        ['label'=>'Neutered',     'val'=>$pet->is_neutered,     'icon'=>'bi-scissors'],
                        ['label'=>'Microchipped', 'val'=>$pet->is_microchipped, 'icon'=>'bi-cpu'],
                        ['label'=>'Good w/ Kids', 'val'=>$pet->good_with_kids,  'icon'=>'bi-person-hearts'],
                        ['label'=>'Good w/ Dogs', 'val'=>$pet->good_with_dogs,  'icon'=>'bi-emoji-smile'],
                        ['label'=>'Good w/ Cats', 'val'=>$pet->good_with_cats,  'icon'=>'bi-emoji-smile'],
                    ] as $detail)
                    <div class="col-6 col-sm-4">
                        <div class="d-flex align-items-center gap-2 p-2" style="background:var(--card-bg);border-radius:0.7rem;border:1px solid var(--tan)">
                            <i class="bi {{ $detail['icon'] }}" style="color:{{ $detail['val'] ? 'var(--sage)' : 'var(--muted)' }};font-size:1.1rem"></i>
                            <div>
                                <div style="font-size:0.72rem;color:var(--muted)">{{ $detail['label'] }}</div>
                                <div style="font-size:0.82rem;font-weight:500;color:{{ $detail['val'] ? 'var(--sage)' : 'var(--muted)' }}">
                                    {{ $detail['val'] ? 'Yes' : 'No' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Shelter Info --}}
                <div class="d-flex align-items-center gap-3 p-3 mb-4" style="background:var(--warm-white);border-radius:0.9rem;border:1px solid var(--tan)">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--terra-light);display:flex;align-items:center;justify-content:center;font-size:1.3rem">🏠</div>
                    <div>
                        <div style="font-weight:500;font-size:0.9rem;color:var(--cocoa)">{{ $pet->shelter->name }}</div>
                        <div style="font-size:0.8rem;color:var(--muted)">📍 {{ $pet->shelter->city }}  @if($pet->shelter->phone) · 📞 {{ $pet->shelter->phone }} @endif</div>
                    </div>
                </div>

                {{-- CTA --}}
                @auth
                    <div class="d-flex gap-3">
                        <a href="{{ route('adoptions.create', $pet) }}" class="btn btn-terra flex-fill py-3 fw-500">
                            🐾 Apply to Adopt {{ $pet->name }}
                        </a>
                        <form action="{{ route('favorites.toggle', $pet) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-cocoa py-3 px-4">
                                <i class="bi bi-heart{{ $isFavorited ? '-fill text-terra' : '' }}"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert-terra p-3 rounded-3 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <a href="{{ route('login') }}" style="color:var(--terra-dark);font-weight:500">Log in</a> or
                        <a href="{{ route('register') }}" style="color:var(--terra-dark);font-weight:500">sign up</a>
                        to apply for adoption.
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-terra w-100 py-3">Create Free Account to Adopt</a>
                @endauth
            </div>
        </div>

        {{-- ---- RELATED PETS ---- --}}
        @if($related->count())
        <div class="mt-5 pt-4 border-top" style="border-color:var(--tan) !important">
            <h3 class="font-display mb-4">More {{ $pet->species_label }}s you might like</h3>
            <div class="row g-4">
                @foreach($related as $rp)
                <div class="col-sm-6 col-lg-3">
                    <div class="pet-card h-100" onclick="window.location='{{ route('pets.show', $rp) }}'">
                        <div class="pet-img-wrap">
                            <img src="{{ $rp->cover_url }}" alt="{{ $rp->name }}" loading="lazy">
                        </div>
                        <div class="p-3">
                            <div class="pet-name mb-1">{{ $rp->name }}</div>
                            <div style="font-size:0.82rem;color:var(--muted)">{{ $rp->breed }} · {{ $rp->age_string }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
