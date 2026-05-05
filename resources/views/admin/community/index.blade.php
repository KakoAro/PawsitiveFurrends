@extends('layouts.app')
@section('title', 'Admin — Community Posts')

@section('content')
<div class="d-flex">
    <div class="admin-sidebar">
        <div class="brand">🐾 Pawsitive Furrends</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link active" href="{{ route('admin.community.index') }}"><i class="bi bi-images me-2"></i>Community Posts</a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
        </nav>
    </div>

    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <h2 class="font-display mb-4">Community Posts</h2>

        @if(session('success'))
        <div class="alert alert-terra alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:1.2rem">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:var(--cream)">
                        <tr>
                            <th style="padding:1rem 1.2rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Post</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Category</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Posted By</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Status</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td style="padding:0.9rem 1.2rem">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $post->image_url }}" style="width:44px;height:44px;border-radius:0.6rem;object-fit:cover">
                                    <div>
                                        <div style="font-weight:500;color:var(--cocoa);font-size:0.9rem">{{ $post->title }}</div>
                                        <div style="font-size:0.75rem;color:var(--muted)">{{ Str::limit($post->description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span style="font-size:0.78rem;font-weight:600;background:{{ $post->category_color }};color:#fff;padding:3px 10px;border-radius:50px">{{ $post->category_label }}</span></td>
                            <td style="font-size:0.85rem;color:var(--muted)">{{ $post->user->name }}</td>
                            <td>
                                <span class="badge" style="background:{{ $post->status === 'approved' ? 'var(--sage-light)' : ($post->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $post->status === 'approved' ? 'var(--sage)' : ($post->status === 'rejected' ? '#dc2626' : '#d97706') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.community.status', $post) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-sm" style="background:var(--sage-light);color:var(--sage)" type="submit">✓ Approve</button>
                                    </form>
                                    <form action="{{ route('admin.community.status', $post) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">✕ Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5" style="color:var(--muted)">No posts submitted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    </div>
</div>
@endsection