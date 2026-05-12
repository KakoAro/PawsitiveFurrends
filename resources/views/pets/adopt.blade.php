@extends('layouts.app')
@section('title', 'Adopt ' . $pet->name)

@section('content')
<div style="padding-top:80px"></div>

<section class="py-5">
    <div class="container" style="max-width:800px">

        {{-- Pet Summary --}}
        <div class="d-flex align-items-center gap-4 p-4 mb-4" style="background:var(--card-bg);border-radius:1.2rem;border:1px solid var(--tan)">
            <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}"
                 style="width:90px;height:90px;border-radius:0.9rem;object-fit:cover">
            <div>
                <div style="font-size:0.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px">Adopting</div>
                <h2 class="font-display mb-1" style="font-size:1.8rem">{{ $pet->name }}</h2>
                <div style="font-size:0.88rem;color:var(--muted)">{{ $pet->breed }} · {{ $pet->age_string }} · {{ $pet->shelter->name }}</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
            <div class="card-body p-5">
                <h3 class="font-display mb-1">Adoption Application</h3>
                <p style="color:var(--muted);font-size:0.9rem;margin-bottom:2rem">Please fill out this form completely. We review all applications within 24 hours.</p>

                <form action="{{ route('adoptions.store', $pet) }}" method="POST">
                    @csrf

                    {{-- Application Type --}}
<div class="mb-4 p-4" style="background:var(--cream);border-radius:1rem;border:1px solid var(--tan)">
    <label class="form-label fw-500 mb-3" style="font-size:0.95rem;color:var(--cocoa)">
        How would you like to proceed? <span class="text-danger">*</span>
    </label>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="d-block cursor-pointer" style="cursor:pointer" onclick="setApplicationType('adopt')">
                <div id="card-adopt" class="p-3 h-100" style="border:2px solid var(--terra);border-radius:0.9rem;background:#fff;transition:all 0.2s">
                    <div class="d-flex align-items-center gap-3">
                        <input type="radio" name="application_type" value="adopt" id="type-adopt" checked
                               style="accent-color:var(--terra);width:18px;height:18px;flex-shrink:0"
                               onchange="setApplicationType('adopt')">
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--cocoa)">Adopt</div>
                            <div style="font-size:0.8rem;color:var(--muted);margin-top:2px">Permanent forever home</div>
                        </div>
                    </div>
                    <ul style="font-size:0.78rem;color:var(--muted);margin-top:0.8rem;margin-bottom:0;padding-left:1.2rem;line-height:1.9">
                        <li>Permanent ownership</li>
                        <li>Full responsibility from day 1</li>
                        <li>Pet stays with you forever</li>
                    </ul>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="d-block" style="cursor:pointer" onclick="setApplicationType('foster')">
                <div id="card-foster" class="p-3 h-100" style="border:2px solid var(--tan);border-radius:0.9rem;background:#fff;transition:all 0.2s">
                    <div class="d-flex align-items-center gap-3">
                        <input type="radio" name="application_type" value="foster" id="type-foster"
                               style="accent-color:var(--terra);width:18px;height:18px;flex-shrink:0"
                               onchange="setApplicationType('foster')">
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--cocoa)">Foster</div>
                            <div style="font-size:0.8rem;color:var(--muted);margin-top:2px">Temporary care with trial period</div>
                        </div>
                    </div>
                    <ul style="font-size:0.78rem;color:var(--muted);margin-top:0.8rem;margin-bottom:0;padding-left:1.2rem;line-height:1.9">
                        <li>1-week trial period</li>
                        <li>Return pet if not a fit</li>
                        <li>Can convert to full adoption</li>
                    </ul>
                </div>
            </label>
        </div>
    </div>

    {{-- Foster warranty notice --}}
    <div id="foster-notice" style="display:none;margin-top:1rem">
        <div class="p-3 d-flex align-items-start gap-3"
             style="background:#e8f5e9;border-radius:0.8rem;border:1px solid #a5d6a7">
            <span style="font-size:1.3rem;flex-shrink:0">🐾</span>
            <div>
                <div style="font-weight:700;font-size:0.88rem;color:var(--sage);margin-bottom:4px">
                    1-Week Foster Warranty
                </div>
                <div style="font-size:0.82rem;color:var(--muted);line-height:1.7">
                    By choosing <strong>Foster</strong>, you agree to care for
                    <strong>{{ $pet->name }}</strong> for a trial period of
                    <strong>7 days</strong>. During this time:
                    <ul style="margin-top:6px;margin-bottom:0;padding-left:1.1rem;line-height:1.9">
                        <li>You may return {{ $pet->name }} to the shelter within 7 days at no penalty</li>
                        <li>If you choose to keep {{ $pet->name }} after 7 days, the foster automatically converts to full adoption</li>
                        <li>You are responsible for {{ $pet->name }}'s food, safety, and vet care during the foster period</li>
                        <li>The shelter may do a check-in visit during the trial week</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

                    {{-- Personal Info --}}
                    <h6 class="text-terra fw-500 mb-3 text-uppercase" style="letter-spacing:0.6px;font-size:0.8rem">Personal Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="applicant_name" class="form-control @error('applicant_name') is-invalid @enderror"
                                   value="{{ old('applicant_name', Auth::user()->name) }}" required>
                            @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="applicant_email" class="form-control @error('applicant_email') is-invalid @enderror"
                                   value="{{ old('applicant_email', Auth::user()->email) }}" required>
                            @error('applicant_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="applicant_phone" class="form-control @error('applicant_phone') is-invalid @enderror"
                                   value="{{ old('applicant_phone') }}" placeholder="+63 9XX XXX XXXX" required>
                            @error('applicant_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Housing Type <span class="text-danger">*</span></label>
                            <select name="housing_type" class="form-select @error('housing_type') is-invalid @enderror" required>
                                <option value="">Select type</option>
                                <option value="house"     {{ old('housing_type') === 'house'     ? 'selected' : '' }}>House</option>
                                <option value="apartment" {{ old('housing_type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="condo"     {{ old('housing_type') === 'condo'     ? 'selected' : '' }}>Condominium</option>
                                <option value="other"     {{ old('housing_type') === 'other'     ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('housing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Home Address <span class="text-danger">*</span></label>
                            <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Street, Barangay, City, Province" required>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_yard" value="1" id="hasYard" {{ old('has_yard') ? 'checked' : '' }}>
                                <label class="form-check-label" for="hasYard" style="font-size:0.88rem">I have a yard or outdoor space</label>
                            </div>
                        </div>
                    </div>

                    {{-- Pet Experience --}}
                    <h6 class="text-terra fw-500 mb-3 text-uppercase" style="letter-spacing:0.6px;font-size:0.8rem">Pet Experience</h6>
                    <div class="row g-3 mb-4">
                       <div class="col-12">
    <label class="form-label">Do you currently have other pets?</label>
    <div class="d-flex gap-4 mb-3 mt-1">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="has_other_pets"
                   id="has_pets_yes" value="yes"
                   {{ old('has_other_pets') === 'yes' ? 'checked' : '' }}
                   onchange="document.getElementById('other_pets_field').style.display='block'">
            <label class="form-check-label" for="has_pets_yes" style="font-size:0.9rem;font-weight:500">
                Yes, I have other pets
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="has_other_pets"
                   id="has_pets_no" value="no"
                   {{ old('has_other_pets') === 'no' ? 'checked' : '' }}
                   onchange="document.getElementById('other_pets_field').style.display='none'">
            <label class="form-check-label" for="has_pets_no" style="font-size:0.9rem;font-weight:500">
                No, I don't have other pets
            </label>
        </div>
    </div>
    <div id="other_pets_field" style="display:{{ old('has_other_pets') === 'yes' ? 'block' : 'none' }}">
        <label class="form-label" style="font-size:0.85rem;color:var(--muted)">Please describe your other pets</label>
        <textarea name="other_pets" rows="2" class="form-control"
                  placeholder="e.g. 1 adult cat, neutered male, very calm">{{ old('other_pets') }}</textarea>
    </div>
</div>

                    {{-- Reason --}}
                    <h6 style="color:var(--muted);font-weight:500;letter-spacing:0.6px;font-size:0.8rem;text-transform:uppercase">Why Adopt {{ $pet->name }}?</h6>
                    <div class="mb-4">
                        <label class="form-label">Tell us why you'd like to adopt {{ $pet->name }} <span class="text-danger">*</span></label>
                        <textarea name="reason" rows="5" class="form-control @error('reason') is-invalid @enderror"
                                  placeholder="Share your reasons for adopting, your lifestyle, daily routine, and how you plan to care for {{ $pet->name }}. (Minimum 10 characters)" required>{{ old('reason') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">Minimum 10 characters</div>
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-cocoa px-4 py-3 flex-fill">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-terra px-4 py-3 flex-fill">
                            Submit Application 🐾
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function setApplicationType(type) {
    const cardAdopt  = document.getElementById('card-adopt');
    const cardFoster = document.getElementById('card-foster');
    const notice     = document.getElementById('foster-notice');

    if (type === 'foster') {
        cardFoster.style.borderColor = 'var(--terra)';
        cardFoster.style.background  = 'rgba(196,113,74,0.04)';
        cardAdopt.style.borderColor  = 'var(--tan)';
        cardAdopt.style.background   = '#fff';
        notice.style.display = 'block';
        document.getElementById('type-foster').checked = true;
    } else {
        cardAdopt.style.borderColor  = 'var(--terra)';
        cardAdopt.style.background   = 'rgba(196,113,74,0.04)';
        cardFoster.style.borderColor = 'var(--tan)';
        cardFoster.style.background  = '#fff';
        notice.style.display = 'none';
        document.getElementById('type-adopt').checked = true;
    }
}
</script>
@endpush
