@extends('layouts.app')
@section('title', 'Admin — Adoptions')

@section('content')
<div class="d-flex">
    <div class="admin-sidebar">
        <div class="brand">🐾 PawHome Admin</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link active" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
        </nav>
    </div>

    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="font-display mb-0">Adoption Applications</h2>
            {{-- Status filter --}}
            <div class="d-flex gap-2 flex-wrap">
                @foreach([''=>'All','pending'=>'Pending','reviewing'=>'Reviewing','approved'=>'Approved','rejected'=>'Rejected','completed'=>'Completed'] as $val => $label)
                <a href="{{ route('admin.adoptions.index') }}{{ $val ? '?status='.$val : '' }}"
                   class="filter-tab {{ request('status', '') === $val ? 'active' : '' }}" style="font-size:0.8rem">{{ $label }}</a>
                @endforeach
            </div>
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
                            <th style="padding:1rem 1.2rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">#</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Pet</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Applicant</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Shelter</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Status</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Applied</th>
                            <th style="padding:1rem;font-size:0.78rem;color:var(--muted);font-weight:500;text-transform:uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adoptions as $app)
                        <tr>
                            <td style="padding:0.9rem 1.2rem;font-size:0.82rem;color:var(--muted)">#{{ $app->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $app->pet->cover_url }}" style="width:36px;height:36px;border-radius:0.5rem;object-fit:cover">
                                    <span style="font-weight:500;font-size:0.88rem">{{ $app->pet->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:500;font-size:0.88rem">{{ $app->applicant_name }}</div>
                                <div style="font-size:0.75rem;color:var(--muted)">{{ $app->applicant_email }}</div>
                            </td>
                            <td style="font-size:0.85rem;color:var(--muted)">{{ $app->shelter->name }}</td>
                            <td>
                                <span class="badge badge-{{ $app->status_badge }}">{{ ucfirst($app->status) }}</span>
                            </td>
                            <td style="font-size:0.82rem;color:var(--muted)">{{ $app->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.adoptions.show', $app) }}" class="btn btn-sm btn-outline-cocoa">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color:var(--muted)">No applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $adoptions->links() }}</div>
    </div>
</div>
@endsection
