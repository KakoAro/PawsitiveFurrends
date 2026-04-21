@extends('layouts.app')
@section('title', 'My Favorites')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container">
        <h2 class="font-display mb-1">My Favorite Pets</h2>
        <p style="color:var(--muted);font-size:0.9rem;margin-bottom:2.5rem">Pets you've saved — don't wait too long, they go fast!</p>

        <div class="row g-4">
            @forelse($pets as $pet)
            <div class="col-sm-6 col-lg-3">
                <div class="pet-card h-100" onclick="window.location='{{ route('pets.show', $pet) }}'">
                    <div class="pet-img-wrap">
                        <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}" loading="lazy">
                        <span class="pet-badge-species">{{ $pet->species_label }}</span>
                        <form action="{{ route('favorites.toggle', $pet) }}" method="POST" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="btn-fav active" title="Remove from favorites">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    </div>
                    <div class="p-3">
                        <div class="pet-name mb-1">{{ $pet->name }}</div>
                        <div style="font-size:0.82rem;color:var(--muted)">{{ $pet->breed }} · {{ $pet->age_string }}</div>
                        <div class="d-flex flex-wrap gap-1 mt-2 mb-3">
                            @foreach($pet->tags->take(3) as $tag)
                                <span class="pet-tag">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-terra btn-sm w-100" onclick="event.stopPropagation()">
                            View & Adopt
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div style="font-size:3rem">🤍</div>
                <h5 class="font-display mt-3">No favorites yet</h5>
                <p style="color:var(--muted)">Tap the heart icon on any pet to save them here.</p>
                <a href="{{ route('pets.index') }}" class="btn btn-terra px-4 py-2 mt-2">Browse Pets</a>
            </div>
            @endforelse
        </div>

        @if($pets->hasPages())
        <div class="d-flex justify-content-center mt-5">{{ $pets->links() }}</div>
        @endif
    </div>
</section>
@endsection
