@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container" style="max-width:960px">

        {{-- Profile Header --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:1.5rem;background:var(--card-bg);overflow:hidden">
            <div style="height:120px;background:linear-gradient(135deg,var(--terra-light),var(--sage-light))"></div>
            <div class="px-5 pb-4" style="margin-top:-50px">
                <div class="d-flex align-items-end gap-4 flex-wrap">
                    <div style="width:90px;height:90px;border-radius:50%;background:var(--terra);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;border:4px solid #fff;flex-shrink:0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-fill pb-2">
                        <h2 class="font-display mb-1">{{ $user->name }}</h2>
                        <div style="color:var(--muted);font-size:0.88rem">{{ $user->email }}</div>
                        @if($user->phone)
                        <div style="color:var(--muted);font-size:0.88rem">📞 {{ $user->phone }}</div>
                        @endif
                    </div>
                    <div class="d-flex gap-3 pb-2">
                        <div class="text-center">
                            <div class="font-display fw-bold" style="font-size:1.5rem;color:var(--cocoa)">{{ $adoptions->count() }}</div>
                            <div style="font-size:0.75rem;color:var(--muted)">Applications</div>
                        </div>
                        <div class="text-center">
                            <div class="font-display fw-bold" style="font-size:1.5rem;color:var(--cocoa)">{{ $communityPosts->count() }}</div>
                            <div style="font-size:0.75rem;color:var(--muted)">Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="font-display fw-bold" style="font-size:1.5rem;color:var(--cocoa)">{{ $user->created_at->format('Y') }}</div>
                            <div style="font-size:0.75rem;color:var(--muted)">Member Since</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav mb-4 gap-2" id="profileTabs">
            <li class="nav-item">
                <button class="filter-tab active" onclick="showTab('adoptions', this)">
                    📋 Adoption Applications <span class="badge ms-1" style="background:var(--terra);color:#fff;border-radius:50px;font-size:0.7rem;padding:2px 7px">{{ $adoptions->count() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="filter-tab" onclick="showTab('community', this)">
                    🐾 Community Posts <span class="badge ms-1" style="background:var(--terra);color:#fff;border-radius:50px;font-size:0.7rem;padding:2px 7px">{{ $communityPosts->count() }}</span>
                </button>
            </li>
        </ul>

        {{-- ADOPTION APPLICATIONS TAB --}}
        <div id="tab-adoptions">
            @forelse($adoptions as $adoption)
            <div class="card border-0 shadow-sm mb-3" style="border-radius:1.2rem;background:var(--card-bg)">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <img src="{{ $adoption->pet->cover_url }}" alt="{{ $adoption->pet->name }}"
                             style="width:70px;height:70px;border-radius:0.8rem;object-fit:cover;flex-shrink:0">
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="font-display mb-1">{{ $adoption->pet->name }}</h5>
                                    <div style="font-size:0.85rem;color:var(--muted)">
                                        {{ $adoption->pet->breed }} · {{ $adoption->shelter->name }}
                                    </div>
                                    <div style="font-size:0.8rem;color:var(--muted);margin-top:4px">
                                        📅 Applied {{ $adoption->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="badge px-3 py-2" style="font-size:0.82rem;border-radius:50px;background:{{ match($adoption->status) {'approved'=>'var(--sage-light)','rejected'=>'#fee2e2','pending'=>'#fef3c7','reviewing'=>'#dbeafe',default=>'var(--cream)'} }};color:{{ match($adoption->status) {'approved'=>'var(--sage)','rejected'=>'#dc2626','pending'=>'#d97706','reviewing'=>'#2563eb',default=>'var(--muted)'} }}">
                                    {{ ucfirst($adoption->status) }}
                                </span>
                            </div>
                            @if($adoption->admin_notes)
                            <div class="mt-2 p-2 rounded-2" style="background:var(--warm-white);font-size:0.82rem;color:var(--muted)">
                                <i class="bi bi-chat-left-text me-1"></i><strong>Shelter note:</strong> {{ $adoption->admin_notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div style="font-size:3rem">📋</div>
                <h5 class="font-display mt-3">No applications yet</h5>
                <p style="color:var(--muted)">Browse our pets and apply to adopt one!</p>
                <a href="{{ route('pets.index') }}" class="btn btn-terra px-4 py-2 mt-2">Find a Pet</a>
            </div>
            @endforelse
        </div>

        {{-- COMMUNITY POSTS TAB --}}
        <div id="tab-community" style="display:none">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('community.create') }}" class="btn btn-terra px-4">+ New Post</a>
            </div>
            @forelse($communityPosts as $post)
            <div class="card border-0 shadow-sm mb-3" style="border-radius:1.2rem;background:var(--card-bg)">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                             style="width:70px;height:70px;border-radius:0.8rem;object-fit:cover;flex-shrink:0">
                        <div class="flex-fill">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <span style="font-size:0.72rem;font-weight:600;background:{{ $post->category_color }};color:#fff;padding:2px 10px;border-radius:50px">
                                        {{ $post->category_label }}
                                    </span>
                                    <h5 class="font-display mt-1 mb-1">{{ $post->title }}</h5>
                                    <div style="font-size:0.82rem;color:var(--muted)">
                                        @if($post->location) 📍 {{ $post->location }} · @endif
                                        {{ $post->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="badge px-3 py-2" style="font-size:0.82rem;border-radius:50px;background:{{ $post->status === 'approved' ? 'var(--sage-light)' : ($post->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $post->status === 'approved' ? 'var(--sage)' : ($post->status === 'rejected' ? '#dc2626' : '#d97706') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                            <p style="font-size:0.85rem;color:var(--muted);margin-top:0.5rem;line-height:1.6">
                                {{ Str::limit($post->description, 120) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div style="font-size:3rem">📷</div>
                <h5 class="font-display mt-3">No posts yet</h5>
                <p style="color:var(--muted)">Share a stray, lost, or rescued pet to help your community!</p>
                <a href="{{ route('community.create') }}" class="btn btn-terra px-4 py-2 mt-2">Create First Post</a>
            </div>
            @endforelse
        </div>

    </div>
</section>

@push('scripts')
<script>
function showTab(tab, btn) {
    document.getElementById('tab-adoptions').style.display = 'none';
    document.getElementById('tab-community').style.display = 'none';
    document.getElementById('tab-' + tab).style.display = 'block';
    document.querySelectorAll('#profileTabs .filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>
@endpush
@endsection