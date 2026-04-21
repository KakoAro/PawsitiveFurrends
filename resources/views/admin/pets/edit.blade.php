@extends('layouts.app')
@section('title', isset($pet) ? 'Edit ' . $pet->name : 'Add New Pet')

@section('content')
<div class="d-flex">
    {{-- Sidebar --}}
    <div class="admin-sidebar">
        <div class="brand">🐾 PawHome Admin</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link active" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
        </nav>
    </div>

    {{-- Content --}}
    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-cocoa btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="font-display mb-0">{{ isset($pet) ? 'Edit ' . $pet->name : 'Add New Pet' }}</h2>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:1.2rem;background:var(--card-bg)">
            <div class="card-body p-5">
                <form action="{{ isset($pet) ? route('admin.pets.update', $pet) : route('admin.pets.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($pet)) @method('PUT') @endif

                    <div class="row g-4">

                        {{-- Basic Info --}}
                        <div class="col-12">
                            <h6 class="text-terra text-uppercase fw-500 mb-3" style="letter-spacing:0.6px;font-size:0.8rem">Basic Information</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Shelter <span class="text-danger">*</span></label>
                            <select name="shelter_id" class="form-select @error('shelter_id') is-invalid @enderror" required>
                                <option value="">Select shelter</option>
                                @foreach($shelters as $shelter)
                                <option value="{{ $shelter->id }}" {{ old('shelter_id', $pet->shelter_id ?? '') == $shelter->id ? 'selected' : '' }}>
                                    {{ $shelter->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('shelter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $pet->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Species <span class="text-danger">*</span></label>
                            <select name="species" class="form-select @error('species') is-invalid @enderror" required>
                                @foreach(['dog'=>'Dog','cat'=>'Cat','rabbit'=>'Rabbit','bird'=>'Bird','other'=>'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('species', $pet->species ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Breed</label>
                            <input type="text" name="breed" class="form-control"
                                   value="{{ old('breed', $pet->breed ?? '') }}" placeholder="e.g. Golden Retriever">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                @foreach(['male'=>'Male','female'=>'Female','unknown'=>'Unknown'] as $val => $label)
                                <option value="{{ $val }}" {{ old('gender', $pet->gender ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Age (Years) <span class="text-danger">*</span></label>
                            <input type="number" name="age_years" class="form-control" min="0"
                                   value="{{ old('age_years', $pet->age_years ?? 0) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Age (Months) <span class="text-danger">*</span></label>
                            <input type="number" name="age_months" class="form-control" min="0" max="11"
                                   value="{{ old('age_months', $pet->age_months ?? 0) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Size <span class="text-danger">*</span></label>
                            <select name="size" class="form-select" required>
                                @foreach(['small'=>'Small','medium'=>'Medium','large'=>'Large','extra_large'=>'Extra Large'] as $val => $label)
                                <option value="{{ $val }}" {{ old('size', $pet->size ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" name="weight_kg" class="form-control" step="0.1" min="0"
                                   value="{{ old('weight_kg', $pet->weight_kg ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control"
                                   value="{{ old('color', $pet->color ?? '') }}" placeholder="e.g. Golden, Black & White">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                @foreach(['available'=>'Available','pending'=>'Pending','adopted'=>'Adopted','removed'=>'Removed'] as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $pet->status ?? 'available') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end pb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured"
                                       {{ old('featured', $pet->featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">⭐ Featured on homepage</label>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <hr style="border-color:var(--tan)">
                            <h6 class="text-terra text-uppercase fw-500 mb-3 mt-2" style="letter-spacing:0.6px;font-size:0.8rem">Description</h6>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Pet Description</label>
                            <textarea name="description" rows="4" class="form-control"
                                      placeholder="Describe the pet's personality, habits, and what makes them special...">{{ old('description', $pet->description ?? '') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Health Notes</label>
                            <textarea name="health_notes" rows="2" class="form-control"
                                      placeholder="Any medical conditions, medications, or special care needs...">{{ old('health_notes', $pet->health_notes ?? '') }}</textarea>
                        </div>

                        {{-- Health Details --}}
                        <div class="col-12">
                            <hr style="border-color:var(--tan)">
                            <h6 class="text-terra text-uppercase fw-500 mb-3 mt-2" style="letter-spacing:0.6px;font-size:0.8rem">Health & Compatibility</h6>
                        </div>

                        @foreach([
                            ['name'=>'is_vaccinated',  'label'=>'Vaccinated'],
                            ['name'=>'is_neutered',    'label'=>'Neutered / Spayed'],
                            ['name'=>'is_microchipped','label'=>'Microchipped'],
                            ['name'=>'good_with_kids', 'label'=>'Good with Kids'],
                            ['name'=>'good_with_dogs', 'label'=>'Good with Dogs'],
                            ['name'=>'good_with_cats', 'label'=>'Good with Cats'],
                        ] as $cb)
                        <div class="col-md-4 col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="{{ $cb['name'] }}" value="1" id="{{ $cb['name'] }}"
                                       {{ old($cb['name'], $pet->{$cb['name']} ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $cb['name'] }}">{{ $cb['label'] }}</label>
                            </div>
                        </div>
                        @endforeach

                        {{-- Tags --}}
                        <div class="col-12">
                            <hr style="border-color:var(--tan)">
                            <h6 class="text-terra text-uppercase fw-500 mb-3 mt-2" style="letter-spacing:0.6px;font-size:0.8rem">Tags</h6>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                <div class="form-check" style="margin:0">
                                    <input class="form-check-input" type="checkbox" name="tags[]"
                                           value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                           {{ in_array($tag->id, old('tags', isset($pet) ? $pet->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                    <label class="form-check-label pet-tag" for="tag_{{ $tag->id }}"
                                           style="cursor:pointer">{{ $tag->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Image Upload --}}
                        <div class="col-12">
                            <hr style="border-color:var(--tan)">
                            <h6 class="text-terra text-uppercase fw-500 mb-3 mt-2" style="letter-spacing:0.6px;font-size:0.8rem">Cover Photo</h6>
                        </div>

                        <div class="col-12">
                            @if(isset($pet) && $pet->cover_image)
                            <div class="mb-3">
                                <img src="{{ $pet->cover_url }}" alt="{{ $pet->name }}"
                                     style="width:120px;height:120px;border-radius:0.9rem;object-fit:cover">
                                <div style="font-size:0.78rem;color:var(--muted);margin-top:6px">Current cover photo</div>
                            </div>
                            @endif
                            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                                   accept="image/*">
                            <div style="font-size:0.78rem;color:var(--muted);margin-top:4px">Max 2MB. JPG, PNG, or WebP.</div>
                            @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 d-flex gap-3 pt-3">
                            <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-cocoa px-5 py-3">Cancel</a>
                            <button type="submit" class="btn btn-terra px-5 py-3">
                                <i class="bi bi-check-lg me-2"></i>{{ isset($pet) ? 'Update Pet' : 'Add Pet' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
