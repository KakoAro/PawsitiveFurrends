@extends('layouts.app')
@section('title', 'My Applications')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container" style="max-width:860px">
        <h2 class="font-display mb-1">My Adoption Applications</h2>
        <p style="color:var(--muted);font-size:0.9rem;margin-bottom:2.5rem">Track the status of your adoption applications here.</p>

        @forelse($applications as $app)
        <div class="card border-0 shadow-sm mb-4" style="border-radius:1.2rem;background:var(--card-bg)">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <img src="{{ $app->pet->cover_url }}" alt="{{ $app->pet->name }}"
                         style="width:80px;height:80px;border-radius:0.9rem;object-fit:cover">
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h5 class="font-display mb-1">{{ $app->pet->name }}</h5>
                                <div style="font-size:0.85rem;color:var(--muted)">{{ $app->pet->breed }} · {{ $app->shelter->name }}</div>
                            </div>
                            <span class="badge badge-{{ $app->status_badge }} px-3 py-2" style="font-size:0.82rem">
                                {{ ucfirst($app->status) }}
                            </span>
                        </div>
                        <div class="mt-2 d-flex gap-4 flex-wrap" style="font-size:0.82rem;color:var(--muted)">
                            <span><i class="bi bi-calendar me-1"></i>Applied {{ $app->created_at->format('M d, Y') }}</span>
                            @if($app->reviewed_at)
                            <span><i class="bi bi-clock me-1"></i>Reviewed {{ $app->reviewed_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        @if($app->admin_notes)
                        <div class="mt-2 p-2 rounded-2" style="background:var(--warm-white);font-size:0.82rem;color:var(--muted)">
                            <i class="bi bi-chat-left-text me-1"></i><strong>Shelter note:</strong> {{ $app->admin_notes }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <div style="font-size:3rem">🐾</div>
            <h5 class="font-display mt-3">No applications yet</h5>
            <p style="color:var(--muted)">Browse our pets and submit your first adoption application!</p>
            <a href="{{ route('pets.index') }}" class="btn btn-terra px-4 py-2 mt-2">Find a Pet</a>
        </div>
        @endforelse

        @if($applications->hasPages())
        <div class="d-flex justify-content-center mt-4">{{ $applications->links() }}</div>
        @endif
    </div>
</section>
@endsection
