@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container" style="max-width:980px">

        {{-- Profile Header --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:1.5rem;background:var(--card-bg);overflow:hidden">
            <div style="height:130px;background:linear-gradient(135deg,var(--terra-light),var(--sage-light))"></div>
            <div class="px-5 pb-4" style="margin-top:-55px">
                <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-end gap-4">
                        {{-- Avatar --}}
                        <div style="width:95px;height:95px;border-radius:50%;background:var(--terra);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;border:4px solid #fff;flex-shrink:0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="pb-2">
                            <h2 class="font-display mb-0">{{ $user->name }}</h2>
                            <div style="color:var(--muted);font-size:0.85rem">{{ $user->email }}</div>
                            @if($user->phone)
                            <div style="color:var(--muted);font-size:0.85rem">📞 {{ $user->phone }}</div>
                            @endif
                            <div style="font-size:0.78rem;color:var(--muted);margin-top:3px">
                                <i class="bi bi-calendar3 me-1"></i>Member since {{ $user->created_at->format('F Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Stats + Notification Bell --}}
                    <div class="d-flex align-items-center gap-3 pb-2">
                        {{-- Stats --}}
                        <div class="d-flex gap-3">
                            <div class="text-center px-3 py-2" style="background:var(--cream);border-radius:0.9rem">
                                <div class="font-display fw-bold" style="font-size:1.4rem;color:var(--cocoa)">{{ $adoptions->count() }}</div>
                                <div style="font-size:0.72rem;color:var(--muted)">Applications</div>
                            </div>
                            <div class="text-center px-3 py-2" style="background:var(--cream);border-radius:0.9rem">
                                <div class="font-display fw-bold" style="font-size:1.4rem;color:var(--cocoa)">{{ $communityPosts->count() }}</div>
                                <div style="font-size:0.72rem;color:var(--muted)">Posts</div>
                            </div>
                        </div>

                        {{-- Notification Bell --}}
                        @if(Auth::user()->role === 'admin')
                        @php
                            $pendingAdoptions  = \App\Models\Adoption::where('status','pending')->count();
                            $pendingCommunity  = \App\Models\CommunityPost::where('status','pending')->count();
                            $totalNotif        = $pendingAdoptions + $pendingCommunity;
                        @endphp
                        <div class="dropdown">
                            <button class="btn position-relative" data-bs-toggle="dropdown"
                                    style="width:44px;height:44px;border-radius:50%;background:var(--cream);border:1px solid var(--tan);padding:0;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-bell" style="font-size:1.2rem;color:var(--cocoa-mid)"></i>
                                @if($totalNotif > 0)
                                <span class="position-absolute top-0 end-0 badge rounded-pill"
                                      style="background:var(--terra);font-size:0.6rem;padding:3px 5px;transform:translate(3px,-3px)">
                                    {{ $totalNotif }}
                                </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow p-0"
                                 style="width:300px;border-radius:1rem;overflow:hidden;border:0.5px solid var(--tan)">
                                <div class="p-3" style="background:var(--cocoa);color:#fff">
                                    <div style="font-weight:600;font-size:0.88rem">🔔 Pending Notifications</div>
                                    <div style="font-size:0.73rem;opacity:0.7">{{ $totalNotif }} need your attention</div>
                                </div>

                                @if($pendingAdoptions > 0)
                                <a href="{{ route('admin.adoptions.index') }}?status=pending"
                                   class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                   style="border-bottom:1px solid var(--tan)"
                                   onmouseover="this.style.background='var(--cream)'"
                                   onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--terra-light);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">📋</div>
                                    <div class="flex-fill">
                                        <div style="font-weight:600;font-size:0.82rem;color:var(--cocoa)">Adoption Applications</div>
                                        <div style="font-size:0.75rem;color:var(--muted)">{{ $pendingAdoptions }} pending review</div>
                                    </div>
                                    <span class="badge" style="background:var(--terra-light);color:var(--terra-dark);border-radius:50px">{{ $pendingAdoptions }}</span>
                                </a>
                                @endif

                                @if($pendingCommunity > 0)
                                <a href="{{ route('admin.community.index') }}"
                                   class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                   onmouseover="this.style.background='var(--cream)'"
                                   onmouseout="this.style.background='transparent'">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--sage-light);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">🐾</div>
                                    <div class="flex-fill">
                                        <div style="font-weight:600;font-size:0.82rem;color:var(--cocoa)">Community Posts</div>
                                        <div style="font-size:0.75rem;color:var(--muted)">{{ $pendingCommunity }} awaiting approval</div>
                                    </div>
                                    <span class="badge" style="background:var(--sage-light);color:var(--sage);border-radius:50px">{{ $pendingCommunity }}</span>
                                </a>
                                @endif

                                @if($totalNotif === 0)
                                <div class="p-4 text-center" style="color:var(--muted);font-size:0.82rem">
                                    <div style="font-size:1.8rem;margin-bottom:4px">✅</div>
                                    All caught up!
                                </div>
                                @endif

                                <div class="p-2" style="background:var(--cream)">
                                    <a href="{{ route('admin.pets.index') }}" class="btn btn-terra btn-sm w-100" style="border-radius:50px;font-size:0.8rem">
                                        Go to Admin Panel
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="d-flex gap-2 mb-4 flex-wrap" id="profileTabs">
            <button class="filter-tab active" onclick="showTab('adoptions', this)">
                📋 Adoption Applications
                <span class="badge ms-1" style="background:var(--terra);color:#fff;border-radius:50px;font-size:0.7rem;padding:2px 7px">{{ $adoptions->count() }}</span>
            </button>
            <button class="filter-tab" onclick="showTab('community', this)">
                🐾 My Community Posts
                <span class="badge ms-1" style="background:var(--terra);color:#fff;border-radius:50px;font-size:0.7rem;padding:2px 7px">{{ $communityPosts->count() }}</span>
            </button>
        </div>

        {{-- ADOPTIONS TAB --}}
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
                                    <div style="font-size:0.85rem;color:var(--muted)">{{ $adoption->pet->breed }} · {{ $adoption->shelter->name }}</div>
                                    <div style="font-size:0.8rem;color:var(--muted);margin-top:4px">
                                        📅 Applied {{ $adoption->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="badge px-3 py-2" style="font-size:0.82rem;border-radius:50px;
                                    background:{{ match($adoption->status) {
                                        'approved'  => 'var(--sage-light)',
                                        'rejected'  => '#fee2e2',
                                        'pending'   => '#fef3c7',
                                        'reviewing' => '#dbeafe',
                                        default     => 'var(--cream)'
                                    } }};color:{{ match($adoption->status) {
                                        'approved'  => 'var(--sage)',
                                        'rejected'  => '#dc2626',
                                        'pending'   => '#d97706',
                                        'reviewing' => '#2563eb',
                                        default     => 'var(--muted)'
                                    } }}">
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

       {{-- COMMUNITY POSTS TAB (admin only) --}}
@if(Auth::user()->role === 'admin')

<div id="tab-community" style="display:none">

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
                                @if($post->location)
                                    📍 {{ $post->location }} ·
                                @endif
                                {{ $post->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        <span class="badge px-3 py-2" style="font-size:0.82rem;border-radius:50px;
                            background:{{ $post->status === 'approved' ? 'var(--sage-light)' : ($post->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};
                            color:{{ $post->status === 'approved' ? 'var(--sage)' : ($post->status === 'rejected' ? '#dc2626' : '#d97706') }}">
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

        <a href="{{ route('community.create') }}" class="btn btn-terra px-4 py-2 mt-2">
            Create First Post
        </a>
    </div>
    @endforelse

</div>

@endif

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

// Auto-switch to community tab if coming from community post creation
@if(session('show_tab') === 'community')
document.addEventListener('DOMContentLoaded', () => {
    showTab('community', document.querySelectorAll('#profileTabs .filter-tab')[1]);
});
@endif
</script>
@endpush
@endsection