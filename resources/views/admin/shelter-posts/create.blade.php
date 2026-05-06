@extends('layouts.app')
@section('title', 'New Shelter Post')

@section('content')
<div class="d-flex">
    <div class="admin-sidebar">
        <div class="brand">🐾 Pawsitive Furrends</div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="{{ route('admin.pets.index') }}"><i class="bi bi-paw me-2"></i>All Pets</a>
            <a class="nav-link" href="{{ route('admin.pets.create') }}"><i class="bi bi-plus-circle me-2"></i>Add New Pet</a>
            <a class="nav-link" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Adoptions</a>
            <a class="nav-link" href="{{ route('admin.community.index') }}"><i class="bi bi-images me-2"></i>Community Posts</a>
            <a class="nav-link active" href="{{ route('admin.shelter-posts.index') }}"><i class="bi bi-camera me-2"></i>Shelter Posts</a>
        </nav>
    </div>

    <div class="admin-content flex-fill p-5" style="padding-top:2rem !important">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.shelter-posts.index') }}" class="btn btn-outline-cocoa btn-sm"><i class="bi bi-arrow-left"></i></a>
            <h2 class="font-display mb-0">Create Shelter Post</h2>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:1.2rem;background:var(--card-bg);max-width:700px">
            <div class="card-body p-5">
                <form action="{{ route('admin.shelter-posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="spotlight" {{ old('category') === 'spotlight' ? 'selected' : '' }}>🐾 Pet Spotlight</option>
                            <option value="news"      {{ old('category') === 'news'      ? 'selected' : '' }}>📰 News</option>
                            <option value="event"     {{ old('category') === 'event'     ? 'selected' : '' }}>🎉 Event</option>
                            <option value="update"    {{ old('category') === 'update'    ? 'selected' : '' }}>🏠 Shelter Update</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title') }}"
                               placeholder="e.g. Meet Buddy — Dog of the Month!" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="5" class="form-control"
                                  placeholder="Write a caption or story for this post...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Photo <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required
                               onchange="previewImage(this)">
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">Max 5MB. JPG, PNG or WebP.</div>
                        <div id="preview-wrap" style="display:none;margin-top:10px">
                            <img id="preview-img" src="" style="max-height:200px;border-radius:0.7rem;object-fit:cover">
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.shelter-posts.index') }}" class="btn btn-outline-cocoa px-4 py-2">Cancel</a>
                        <button type="submit" class="btn btn-terra px-5 py-2">
                            <i class="bi bi-upload me-2"></i>Publish Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-wrap').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection