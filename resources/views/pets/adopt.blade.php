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
                            <label class="form-label">Do you have other pets? If yes, describe them.</label>
                            <textarea name="other_pets" rows="2" class="form-control"
                                      placeholder="e.g. 1 adult cat, neutered male, very calm">{{ old('other_pets') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Previous pet experience</label>
                            <textarea name="experience" rows="3" class="form-control"
                                      placeholder="Tell us about any pets you've owned in the past...">{{ old('experience') }}</textarea>
                        </div>
                    </div>

                    {{-- Reason --}}
                    <h6 class="text-terra fw-500 mb-3 text-uppercase" style="letter-spacing:0.6px;font-size:0.8rem">Why Adopt {{ $pet->name }}?</h6>
                    <div class="mb-4">
                        <label class="form-label">Tell us why you'd like to adopt {{ $pet->name }} <span class="text-danger">*</span></label>
                        <textarea name="reason" rows="5" class="form-control @error('reason') is-invalid @enderror"
                                  placeholder="Share your reasons for adopting, your lifestyle, daily routine, and how you plan to care for {{ $pet->name }}. (Minimum 50 characters)" required>{{ old('reason') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">Minimum 50 characters</div>
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
