@extends('layouts.app')
@section('title', 'Review Application #' . $adoption->id)

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
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.adoptions.index') }}" class="btn btn-outline-cocoa btn-sm"><i class="bi bi-arrow-left"></i></a>
            <h2 class="font-display mb-0">Application #{{ $adoption->id }}</h2>
            <span class="badge badge-{{ $adoption->status_badge }} ms-2">{{ ucfirst($adoption->status) }}</span>
        </div>

        @if(session('success'))
        <div class="alert alert-terra alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            {{-- Pet + Applicant Info --}}
            <div class="col-lg-8">
                {{-- Pet Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:1.2rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h6 class="text-terra text-uppercase mb-3" style="font-size:0.78rem;letter-spacing:0.6px">Pet Being Adopted</h6>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $adoption->pet->cover_url }}" style="width:80px;height:80px;border-radius:0.9rem;object-fit:cover">
                            <div>
                                <div class="font-display" style="font-size:1.3rem">{{ $adoption->pet->name }}</div>
                                <div style="font-size:0.85rem;color:var(--muted)">{{ $adoption->pet->breed }} · {{ $adoption->pet->age_string }} · {{ ucfirst($adoption->pet->size) }}</div>
                                <div style="font-size:0.82rem;color:var(--muted)">🏠 {{ $adoption->shelter->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Applicant Info --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:1.2rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h6 class="text-terra text-uppercase mb-3" style="font-size:0.78rem;letter-spacing:0.6px">Applicant Details</h6>
                        <div class="row g-3" style="font-size:0.88rem">
                            <div class="col-sm-6">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Name</div>
                                <div style="font-weight:500">{{ $adoption->applicant_name }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Email</div>
                                <div style="font-weight:500">{{ $adoption->applicant_email }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Phone</div>
                                <div style="font-weight:500">{{ $adoption->applicant_phone }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="color:var(--muted);font-size:0.75px;text-transform:uppercase;letter-spacing:0.4px">Housing</div>
                                <div style="font-weight:500">{{ ucfirst($adoption->housing_type) }} {{ $adoption->has_yard ? '· Has yard ✓' : '' }}</div>
                            </div>
                            <div class="col-12">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Address</div>
                                <div style="font-weight:500">{{ $adoption->address }}</div>
                            </div>
                            @if($adoption->other_pets)
                            <div class="col-12">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Other Pets</div>
                                <div>{{ $adoption->other_pets }}</div>
                            </div>
                            @endif
                            @if($adoption->experience)
                            <div class="col-12">
                                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.4px">Pet Experience</div>
                                <div>{{ $adoption->experience }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="card border-0 shadow-sm" style="border-radius:1.2rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h6 class="text-terra text-uppercase mb-3" style="font-size:0.78rem;letter-spacing:0.6px">Why They Want to Adopt</h6>
                        <p style="color:var(--muted);font-style:italic;line-height:1.8;font-size:0.9rem">"{{ $adoption->reason }}"</p>
                    </div>
                </div>
            </div>

            {{-- Status Update Panel --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius:1.2rem;background:var(--card-bg);position:sticky;top:90px">
                    <div class="card-body p-4">
                        <h6 class="text-terra text-uppercase mb-3" style="font-size:0.78rem;letter-spacing:0.6px">Update Application Status</h6>

                        <form action="{{ route('admin.adoptions.status', $adoption) }}" method="POST">
                            @csrf @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['pending'=>'Pending','reviewing'=>'Reviewing','approved'=>'Approved','rejected'=>'Rejected','completed'=>'Completed'] as $val => $label)
                                    <option value="{{ $val }}" {{ $adoption->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" rows="4" class="form-control"
                                          placeholder="Internal notes about this application...">{{ $adoption->admin_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-terra w-100 py-2">
                                <i class="bi bi-check-lg me-2"></i>Update Status
                            </button>
                        </form>

                        @if($adoption->reviewed_at)
                        <div class="mt-3 pt-3" style="border-top:1px solid var(--tan);font-size:0.78rem;color:var(--muted)">
                            Last reviewed {{ $adoption->reviewed_at->diffForHumans() }}
                            @if($adoption->reviewer) by {{ $adoption->reviewer->name }} @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
