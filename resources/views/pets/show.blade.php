@extends('layouts.app')
@section('title', $pet->name . ' — ' . $pet->breed)

@section('content')
<div style="padding-top:76px"></div>

{{-- Hero Banner --}}
<div style="width:100%;height:380px;overflow:hidden;position:relative">
    <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}"
         style="width:100%;height:100%;object-fit:cover;filter:brightness(0.75)">
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(46,31,19,0.85) 0%,transparent 60%)"></div>
    <div class="container h-100 d-flex align-items-end pb-4" style="position:relative">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0" style="font-size:0.82rem">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.7)">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pets.index') }}" style="color:rgba(255,255,255,0.7)">Pets</a></li>
                    <li class="breadcrumb-item active" style="color:#fff">{{ $pet->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h1 class="font-display mb-0" style="color:#fff;font-size:clamp(2rem,4vw,3rem)">{{ $pet->name }}</h1>
                <span style="background:var(--terra);color:#fff;font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:50px">{{ $pet->species_label }}</span>
                @if($pet->featured)
                <span style="background:rgba(255,255,255,0.2);color:#fff;font-size:0.8rem;padding:5px 14px;border-radius:50px;backdrop-filter:blur(6px)">⭐ Featured</span>
                @endif
            </div>
            <div style="color:rgba(255,255,255,0.8);font-size:0.9rem;margin-top:6px">
                {{ $pet->breed ?? 'Mixed Breed' }} &nbsp;·&nbsp; {{ $pet->age_string }} &nbsp;·&nbsp; {{ ucfirst($pet->size) }} &nbsp;·&nbsp; {{ ucfirst($pet->gender) }}
            </div>
        </div>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5">

            {{-- ===== LEFT COLUMN ===== --}}
            <div class="col-lg-8">

                {{-- Story --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div style="width:48px;height:48px;border-radius:50%;background:var(--terra-light);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0">🐾</div>
                            <div>
                                <h4 class="font-display mb-0">{{ $pet->name }}'s Story</h4>
                                <div style="font-size:0.82rem;color:var(--muted)">Get to know me better</div>
                            </div>
                        </div>
                        <p style="color:var(--muted);line-height:1.9;font-size:0.97rem">
                            {{ $pet->description ?? 'This pet is waiting for someone to write their story. Be the first to give them a forever home!' }}
                        </p>
                        @if($pet->tags->count())
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            @foreach($pet->tags as $tag)
                            <span class="pet-tag" style="font-size:0.82rem;padding:5px 12px">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-5">
                        <h4 class="font-display mb-4">Quick Info</h4>
                        <div class="row g-3">
                            @foreach([
                                ['label'=>'Species', 'value'=> $pet->species_label,       'icon'=>'🐾'],
                                ['label'=>'Breed',   'value'=> $pet->breed ?? 'Mixed',    'icon'=>'🧬'],
                                ['label'=>'Age',     'value'=> $pet->age_string . ' (' . $pet->age_group . ')', 'icon'=>'🎂'],
                                ['label'=>'Size',    'value'=> ucfirst($pet->size),        'icon'=>'📏'],
                                ['label'=>'Gender',  'value'=> ucfirst($pet->gender),      'icon'=>'⚥'],
                                ['label'=>'Color',   'value'=> $pet->color ?? 'N/A',       'icon'=>'🎨'],
                                ['label'=>'Weight',  'value'=> $pet->weight_kg ? $pet->weight_kg . ' kg' : 'N/A', 'icon'=>'⚖️'],
                                ['label'=>'Status',  'value'=> ucfirst($pet->status),      'icon'=>'📌'],
                            ] as $info)
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3" style="background:var(--cream);border-radius:0.9rem">
                                    <div style="font-size:1.5rem;margin-bottom:4px">{{ $info['icon'] }}</div>
                                    <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px">{{ $info['label'] }}</div>
                                    <div style="font-weight:600;font-size:0.88rem;color:var(--cocoa);margin-top:2px">{{ $info['value'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Photo Gallery --}}
                @if($pet->images->count())
                <div class="card border-0 shadow-sm mb-4" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-5">
                        <h4 class="font-display mb-4">More Photos</h4>
                        <div class="row g-3">
                            @foreach($pet->images as $img)
                            <div class="col-6 col-md-4">
                                <div style="border-radius:0.9rem;overflow:hidden;aspect-ratio:1;cursor:pointer"
                                     onclick="document.getElementById('mainHero').src='{{ asset('storage/'.$img->image_path) }}'">
                                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $img->caption }}"
                                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
            {{-- ===== END LEFT COLUMN ===== --}}

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:90px">

                    {{-- Pet Photo --}}
                    <div style="border-radius:1.5rem;overflow:hidden;aspect-ratio:4/3;margin-bottom:1.2rem;box-shadow:0 8px 30px rgba(74,55,40,0.15)">
                        <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}" id="mainHero"
                             style="width:100%;height:100%;object-fit:cover">
                    </div>

                    {{-- Health & Details --}}
                    <div class="card border-0 shadow-sm mb-3" style="border-radius:1.5rem;background:var(--card-bg)">
                        <div class="card-body p-4">
                            <h5 class="font-display mb-3">Health & Details</h5>
                            <div class="row g-2">

                                @foreach([
                                    ['label'=>'Vaccinated',   'val'=>$pet->is_vaccinated, 'icon'=>'bi-shield-check', 'desc'=>'Up to date on vaccines'],
                                    ['label'=>'Neutered',     'val'=>$pet->is_neutered,   'icon'=>'bi-scissors',     'desc'=>'Spayed / Neutered'],
                                    ['label'=>'Good w/ Kids', 'val'=>$pet->good_with_kids,'icon'=>'bi-person-hearts','desc'=>'Kid-friendly'],
                                    ['label'=>'Good w/ Dogs', 'val'=>$pet->good_with_dogs,'icon'=>'bi-emoji-smile',  'desc'=>'Dog-friendly'],
                                    ['label'=>'Good w/ Cats', 'val'=>$pet->good_with_cats,'icon'=>'bi-emoji-smile',  'desc'=>'Cat-friendly'],
                                ] as $detail)
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 p-2"
                                         style="background:{{ $detail['val'] ? 'var(--sage-light)' : '#fff8e1' }};border-radius:0.7rem;border:1px solid {{ $detail['val'] ? 'var(--sage-light)' : '#ffe082' }}">
                                        <div style="width:32px;height:32px;border-radius:50%;background:{{ $detail['val'] ? 'var(--sage)' : '#ffb300' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                            <i class="bi {{ $detail['val'] ? $detail['icon'] : 'bi-exclamation-triangle' }}" style="color:#fff;font-size:0.85rem"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:0.82rem;color:{{ $detail['val'] ? 'var(--sage)' : '#e65100' }}">
                                                {{ $detail['val'] ? '✓ '.$detail['label'] : '⚠ Not '.$detail['label'] }}
                                            </div>
                                            <div style="font-size:0.72rem;color:var(--muted)">
                                                {{ $detail['val'] ? $detail['desc'] : 'May need supervision' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if(!$pet->is_vaccinated || !$pet->is_neutered)
                                <div class="col-12">
                                    <div class="p-2 d-flex align-items-center gap-2" style="background:#fff3e0;border-radius:0.7rem;border:1px solid #ffcc80">
                                        <span style="font-size:1.1rem">⚠️</span>
                                        <div style="font-size:0.78rem;color:#e65100">
                                            Needs vet attention after adoption.
                                            <span style="color:var(--muted)">We'll guide you through it!</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!$pet->good_with_kids || !$pet->good_with_dogs || !$pet->good_with_cats)
                                <div class="col-12">
                                    <div class="p-2 d-flex align-items-center gap-2" style="background:#e8f5e9;border-radius:0.7rem;border:1px solid #a5d6a7">
                                        <span style="font-size:1.1rem">💚</span>
                                        <div style="font-size:0.78rem;color:var(--sage)">
                                            With patience, {{ $pet->name }} can thrive in the right home!
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- Adopt CTA --}}
                    <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
                        <div class="card-body p-4">
                            <h4 class="font-display mb-1">Adopt {{ $pet->name }}</h4>
                            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1.2rem">
                                Give {{ $pet->name }} the forever home they deserve. The adoption process is simple and free!
                            </p>

                            @auth
                                <a href="{{ route('adoptions.create', $pet) }}" class="btn btn-terra w-100 py-3 mb-2" style="border-radius:50px;font-weight:600">
                                    🐾 Apply to Adopt {{ $pet->name }}
                                </a>
                                <form action="{{ route('favorites.toggle', $pet) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-cocoa w-100 py-2" style="border-radius:50px">
                                        <i class="bi bi-heart{{ $isFavorited ? '-fill text-terra' : '' }} me-2"></i>
                                        {{ $isFavorited ? 'Saved to Favorites' : 'Save to Favorites' }}
                                    </button>
                                </form>
                            @else
                                <div class="p-3 mb-3 text-center" style="background:var(--cream);border-radius:0.9rem;font-size:0.88rem;color:var(--muted)">
                                    <i class="bi bi-lock me-1"></i>
                                    <a href="{{ route('login') }}" style="color:var(--terra);font-weight:600">Log in</a> or
                                    <a href="{{ route('register') }}" style="color:var(--terra);font-weight:600">sign up</a>
                                    to adopt {{ $pet->name }}
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-terra w-100 py-3" style="border-radius:50px;font-weight:600">
                                    Create Free Account 🐾
                                </a>
                            @endauth

                            <hr style="border-color:var(--tan);margin:1.2rem 0">

                            <div class="d-flex align-items-center gap-3">
                                <div style="width:46px;height:46px;border-radius:50%;background:var(--terra-light);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">🏠</div>
                                <div>
                                    <div style="font-weight:600;font-size:0.88rem;color:var(--cocoa)">{{ $pet->shelter->name }}</div>
                                    <div style="font-size:0.78rem;color:var(--muted)">📍 {{ $pet->shelter->city }}</div>
                                    @if($pet->shelter->phone)
                                    <div style="font-size:0.78rem;color:var(--muted)">📞 {{ $pet->shelter->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- ===== END RIGHT COLUMN ===== --}}

        </div>

        {{-- Related Pets --}}
        @if($related->count())
        <div class="mt-5 pt-4" style="border-top:1px solid var(--tan)">
            <h3 class="font-display mb-4">More {{ $pet->species_label }}s you might love</h3>
            <div class="row g-4">
                @foreach($related as $rp)
                <div class="col-sm-6 col-lg-3">
                    <div class="pet-card h-100" onclick="window.location='{{ route('pets.show', $rp) }}'">
                        <div class="pet-img-wrap">
                            <img src="{{ $rp->cover_url }}" alt="{{ $rp->name }}" loading="lazy">
                            <span class="pet-badge-species">{{ $rp->species_label }}</span>
                        </div>
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="pet-name">{{ $rp->name }}</div>
                                <span style="font-size:0.75rem;color:var(--muted);background:var(--cream);padding:2px 8px;border-radius:50px">{{ $rp->age_string }}</span>
                            </div>
                            <div style="font-size:0.82rem;color:var(--muted)">{{ $rp->breed ?? 'Mixed' }}</div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @foreach($rp->tags->take(2) as $tag)
                                <span class="pet-tag">{{ $tag->name }}</span>
                                @endforeach
                            </div>
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