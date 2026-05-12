@extends('layouts.app')
@section('title', 'Community — Strays, Rescued & Lost Pets')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
            <div>
                <div class="section-tag">Community Awareness</div>
                <h1 class="font-display mb-1">Strays, Rescued & Lost Pets</h1>
                <p style="color:var(--muted);font-size:0.9rem">Help us spread awareness. Every post can save a life. 🐾</p>
            </div>
            @auth
            <a href="{{ route('community.create') }}" class="btn btn-terra px-4 py-2">
                + Share a Pet
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-terra px-4 py-2">
                Log in to Share
            </a>
            @endauth
        </div>

        {{-- Category Filter --}}
        <div class="d-flex gap-2 flex-wrap mb-4">
            @foreach(['' => 'All', 'stray' => '🐾 Stray', 'rescued' => '💚 Rescued', 'lost' => '🔍 Lost', 'found' => '✅ Found'] as $val => $label)
            <a href="{{ route('community.index') }}{{ $val ? '?category='.$val : '' }}"
               class="filter-tab {{ request('category', '') === $val ? 'active' : '' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;overflow:hidden;background:var(--card-bg)">
                    <div style="aspect-ratio:4/3;overflow:hidden;position:relative">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                             style="width:100%;height:100%;object-fit:cover">
                        <span class="position-absolute" style="top:12px;left:12px;background:{{ $post->category_color }};color:#fff;font-size:0.75rem;font-weight:600;padding:4px 12px;border-radius:50px">
                            {{ $post->category_label }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h5 class="font-display mb-2" style="font-size:1.1rem">{{ $post->title }}</h5>
                        <p style="font-size:0.85rem;color:var(--muted);line-height:1.6;margin-bottom:0.8rem">
                            {{ Str::limit($post->description, 100) }}
                        </p>
                        @if($post->location)
                        <div style="font-size:0.8rem;color:var(--muted);margin-bottom:4px">📍 {{ $post->location }}</div>
                        @endif
                        @if($post->contact)
                        <div style="font-size:0.8rem;color:var(--muted);margin-bottom:0.8rem">📞 {{ $post->contact }}</div>
                        @endif
                        <div style="font-size:0.75rem;color:var(--muted);border-top:1px solid var(--tan);padding-top:0.7rem;margin-top:0.5rem">
                            Posted by <strong>{{ $post->user->name }}</strong> · {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div style="font-size:3rem">🐾</div>
                <h5 class="font-display mt-3">No posts yet</h5>
                <p style="color:var(--muted)">Be the first to share a pet in need!</p>
            </div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="d-flex justify-content-center mt-5">{{ $posts->links() }}</div>
        @endif
    </div>
</section>
@endsection