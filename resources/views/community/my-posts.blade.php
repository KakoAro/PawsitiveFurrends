@extends('layouts.app')
@section('title', 'My Community Posts')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container" style="max-width:860px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-display mb-0">My Community Posts</h2>
            <a href="{{ route('community.create') }}" class="btn btn-terra px-4">+ New Post</a>
        </div>

        @if(session('success'))
        <div class="alert alert-terra alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @forelse($posts as $post)
        <div class="card border-0 shadow-sm mb-3" style="border-radius:1.2rem;background:var(--card-bg)">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    @if($post->image)
                    <img src="{{ $post->image_url }}" style="width:70px;height:70px;border-radius:0.7rem;object-fit:cover;flex-shrink:0">
                    @endif
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <span style="font-size:0.72rem;font-weight:600;background:{{ $post->category_color }};color:#fff;padding:2px 10px;border-radius:50px">{{ $post->category_label }}</span>
                                <h5 class="font-display mt-1 mb-1">{{ $post->title }}</h5>
                                <div style="font-size:0.82rem;color:var(--muted)">{{ $post->created_at->format('M d, Y') }}</div>
                            </div>
                            <span class="badge px-3 py-2" style="font-size:0.8rem;background:{{ $post->status === 'approved' ? 'var(--sage-light)' : ($post->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $post->status === 'approved' ? 'var(--sage)' : ($post->status === 'rejected' ? '#dc2626' : '#d97706') }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
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

        @if($posts->hasPages())
        <div class="d-flex justify-content-center mt-4">{{ $posts->links() }}</div>
        @endif
    </div>
</section>
@endsection