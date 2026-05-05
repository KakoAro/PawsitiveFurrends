@extends('layouts.app')
@section('title', 'Share a Pet')

@section('content')
<div style="padding-top:80px"></div>
<section class="py-5">
    <div class="container" style="max-width:700px">
        <div class="section-tag">Community</div>
        <h1 class="font-display mb-2">Share a Pet in Need</h1>
        <p style="color:var(--muted);margin-bottom:2rem;font-size:0.9rem">
            Help spread awareness about stray, lost, rescued, or found animals in your area.
            All posts are reviewed before being published.
        </p>

        <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
            <div class="card-body p-5">
                <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4" style="font-size:0.88rem">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="stray"        {{ old('category') === 'stray'        ? 'selected' : '' }}>🐾 Stray</option>
                            <option value="rescued"      {{ old('category') === 'rescued'      ? 'selected' : '' }}>💚 Rescued</option>
                            <option value="lost"         {{ old('category') === 'lost'         ? 'selected' : '' }}>🔍 Lost</option>
                            <option value="found"        {{ old('category') === 'found'        ? 'selected' : '' }}>✅ Found</option>
                            <option value="for_adoption" {{ old('category') === 'for_adoption' ? 'selected' : '' }}>🏠 For Adoption</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title') }}"
                               placeholder="e.g. Lost brown Aspin near Katipunan Ave" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control"
                                  placeholder="Describe the pet — color, size, markings, when/where seen, condition. Minimum 20 characters."
                                  required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control"
                                   value="{{ old('location') }}"
                                   placeholder="e.g. Quezon City, near SM North">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact" class="form-control"
                                   value="{{ old('contact') }}"
                                   placeholder="e.g. 09XX XXX XXXX">
                        </div>
                    </div>

                    <div class="mt-3 mb-4">
                        <label class="form-label">Photo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">Max 3MB. JPG, PNG or WebP.</div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('community.index') }}" class="btn btn-outline-cocoa px-4 py-3 flex-fill">Cancel</a>
                        <button type="submit" class="btn btn-terra px-4 py-3 flex-fill">Submit Post 🐾</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection