@extends('layouts.app')
@section('title', 'Admin — Shelter Posts')

@section('content')
<div class="d-flex">
    <div class="admin-sidebar">
        <div class="brand">🐾 Pawsitive Furrends</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link" href="{{ route('admin.community.index') }}"><i class="bi bi-images me-2"></i>Community Posts</a>
            <a class="nav-link active" href="{{ route('admin.shelter-posts.index') }}"><i class="bi bi-camera me-2"></i>Shelter Posts</a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
        </nav>
    </div>

    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="font-display mb-0">Shelter Posts</h2>
                <p style="color:var(--muted);font-size:0.88rem;margin-top:4px">Post official shelter news, events, and pet spotlights</p>
            </div>
            <a href="{{ route('admin.shelter-posts.create') }}" class="btn btn-terra px-4">
                <i class="bi bi-plus-lg me-1"></i>New Post
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-terra alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1.2rem;overflow:hidden;background:var(--card-bg)">
                    <div style="aspect-ratio:16/9;overflow:hidden;position:relative">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                             style="width:100%;height:100%;object-fit:cover">
                        <span class="position-absolute" style="top:10px;left:10px;background:{{ $post->category_color }};color:#fff;font-size:0.72rem;font-weight:600;padding:3px 10px;border-radius:50px">
                            {{ $post->category_label }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h5 class="font-display mb-2" style="font-size:1rem">{{ $post->title }}</h5>
                        @if($post->description)
                        <p style="font-size:0.82rem;color:var(--muted);line-height:1.6;margin-bottom:0.8rem">{{ Str::limit($post->description, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <div style="font-size:0.75rem;color:var(--muted)">{{ $post->created_at->format('M d, Y') }} · {{ $post->author->name }}</div>
                            <form action="{{ route('admin.shelter-posts.destroy', $post) }}" method="POST"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:50px;font-size:0.75rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div style="font-size:3rem">📷</div>
                <h5 class="font-display mt-3">No posts yet</h5>
                <p style="color:var(--muted)">Create your first shelter post to share news and updates!</p>
                <a href="{{ route('admin.shelter-posts.create') }}" class="btn btn-terra px-4 mt-2">Create First Post</a>
            </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $posts->links() }}</div>
    </div>
</div>
@endsection