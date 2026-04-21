{{-- ================================================================
     resources/views/admin/pets/index.blade.php
     ================================================================ --}}
@extends('layouts.app')
@section('title', 'Admin — Manage Pets')

@section('content')
<div class="d-flex">
    {{-- Sidebar --}}
    <div class="admin-sidebar">
        <div class="brand">🐾 PawHome Admin</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link active" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
        </nav>
    </div>

    {{-- Content --}}
    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-display mb-0">Manage Pets</h2>
            <a href="{{ route('admin.pets.create') }}" class="btn btn-terra px-4">
                <i class="bi bi-plus-lg me-1"></i>Add New Pet
            </a>
        </div>

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
                            <th style="padding:1rem 1.2rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase;letter-spacing:0.5px">Pet</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Species</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Shelter</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Status</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Featured</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pets as $pet)
                        <tr>
                            <td style="padding:0.9rem 1.2rem">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}"
                                         style="width:44px;height:44px;border-radius:0.6rem;object-fit:cover">
                                    <div>
                                        <div style="font-weight:500;color:var(--cocoa)">{{ $pet->name }}</div>
                                        <div style="font-size:0.78rem;color:var(--muted)">{{ $pet->breed ?? 'Mixed' }} · {{ $pet->age_string }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge" style="background:var(--terra-light);color:var(--terra-dark)">{{ $pet->species_label }}</span></td>
                            <td style="font-size:0.85rem;color:var(--muted)">{{ $pet->shelter->name }}</td>
                            <td>
                                <span class="badge badge-{{ $pet->status === 'available' ? 'approved' : ($pet->status === 'pending' ? 'pending' : 'rejected') }}">
                                    {{ ucfirst($pet->status) }}
                                </span>
                            </td>
                            <td>
                                @if($pet->featured)
                                    <i class="bi bi-star-fill" style="color:var(--terra)"></i>
                                @else
                                    <i class="bi bi-star" style="color:var(--tan)"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-sm btn-outline-cocoa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST"
                                          onsubmit="return confirm('Delete {{ $pet->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $pets->links() }}</div>
    </div>
</div>
@endsection
