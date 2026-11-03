@extends('layouts.app')
@section('page-title', $industry->exists ? 'Edit Industry' : 'Add Industry')
@section('content')

<x-page-header title="{{ $industry->exists ? 'Edit Industry' : 'Add Industry' }}" sys="CONTENT://INDUSTRIES" />

<form method="POST"
      action="{{ $industry->exists ? route('admin.industries.update', $industry) : route('admin.industries.store') }}"
      enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if($industry->exists)
        @method('PUT')
    @endif

    <div class="term-panel p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Core details</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="term-field-label" for="name">Industry name *</label>
                <input id="name" name="name" required class="term-input" value="{{ old('name', $industry->name) }}">
                @error('name')<p class="term-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="term-field-label" for="slug">Slug</label>
                <input id="slug" name="slug" class="term-input font-mono text-sm" value="{{ old('slug', $industry->slug) }}" placeholder="auto-generated-from-name">
                <p class="mt-1 text-xs text-gray-500">Leave blank to generate from the name.</p>
                @error('slug')<p class="term-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="term-field-label" for="category">Category</label>
                <input id="category" name="category" class="term-input" value="{{ old('category', $industry->category) }}">
                @error('category')<p class="term-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="term-field-label" for="description">Short description *</label>
                <textarea id="description" name="description" rows="3" required maxlength="600" class="term-input">{{ old('description', $industry->description) }}</textarea>
                @error('description')<p class="term-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="term-field-label" for="overview">Industry overview</label>
                <textarea id="overview" name="overview" rows="5" class="term-input">{{ old('overview', $industry->overview) }}</textarea>
            </div>
        </div>
    </div>

    <div class="term-panel p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Card visual</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload an image, or leave blank to use the inline icon.</p>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="term-field-label" for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*" class="term-input">
                @error('image')<p class="term-error">{{ $message }}</p>@enderror
                @if($industry->image_path)
                <label class="mt-2 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="remove_image" value="1" class="rounded"> Remove current image
                </label>
                @endif
            </div>
            <div>
                <label class="term-field-label" for="image_alt">Image alt text</label>
                <input id="image_alt" name="image_alt" class="term-input" value="{{ old('image_alt', $industry->image_alt) }}">
            </div>
            @if($industry->imageUrl())
            <div class="sm:col-span-2">
                <img src="{{ $industry->imageUrl() }}" alt="{{ $industry->image_alt ?: $industry->name }}" class="h-32 w-auto rounded-lg border border-gray-200 dark:border-gray-700">
            </div>
            @endif
            <div class="sm:col-span-2">
                <label class="term-field-label" for="icon">Icon (SVG path data)</label>
                <input id="icon" name="icon" class="term-input font-mono text-xs" value="{{ old('icon', $industry->icon) }}">
            </div>
        </div>
    </div>

    <div class="term-panel p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Content lists</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">One item per line. Tags accept commas or new lines.</p>
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="term-field-label" for="tags">Tags</label>
                <input id="tags" name="tags" class="term-input" value="{{ old('tags', implode(', ', $industry->tags ?? [])) }}">
            </div>
            <div>
                <label class="term-field-label" for="challenges">Common IT challenges</label>
                <textarea id="challenges" name="challenges" rows="5" class="term-input">{{ old('challenges', implode("\n", $industry->challenges ?? [])) }}</textarea>
            </div>
            <div>
                <label class="term-field-label" for="services">Recommended IT support services</label>
                <textarea id="services" name="services" rows="5" class="term-input">{{ old('services', implode("\n", $industry->services ?? [])) }}</textarea>
            </div>
            <div>
                <label class="term-field-label" for="cybersecurity">Cybersecurity requirements</label>
                <textarea id="cybersecurity" name="cybersecurity" rows="5" class="term-input">{{ old('cybersecurity', implode("\n", $industry->cybersecurity ?? [])) }}</textarea>
            </div>
            <div>
                <label class="term-field-label" for="technology">Technology requirements</label>
                <textarea id="technology" name="technology" rows="5" class="term-input">{{ old('technology', implode("\n", $industry->technology ?? [])) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="term-field-label" for="benefits">Benefits of outsourced IT support</label>
                <textarea id="benefits" name="benefits" rows="5" class="term-input">{{ old('benefits', implode("\n", $industry->benefits ?? [])) }}</textarea>
            </div>
        </div>
    </div>

    <div class="term-panel p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">SEO and publishing</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="term-field-label" for="seo_title">SEO title</label>
                <input id="seo_title" name="seo_title" maxlength="255" class="term-input" value="{{ old('seo_title', $industry->seo_title) }}">
            </div>
            <div>
                <label class="term-field-label" for="sort_order">Display order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="term-input" value="{{ old('sort_order', $industry->sort_order ?? 0) }}">
            </div>
            <div class="sm:col-span-2">
                <label class="term-field-label" for="meta_description">Meta description</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="500" class="term-input">{{ old('meta_description', $industry->meta_description) }}</textarea>
            </div>
            <div class="flex flex-col gap-2">
                <label class="term-field-label"><input type="checkbox" name="is_active" value="1" class="rounded" @checked(old('is_active', $industry->is_active))> Active / published</label>
                <label class="term-field-label"><input type="checkbox" name="show_on_homepage" value="1" class="rounded" @checked(old('show_on_homepage', $industry->show_on_homepage))> Show on homepage</label>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="term-btn">{{ $industry->exists ? 'Save Changes' : 'Create Industry' }}</button>
        <a href="{{ route('admin.industries.index') }}" class="term-btn term-btn-ghost">Cancel</a>
    </div>
</form>

@if($industry->exists)
<form method="POST" action="{{ route('admin.industries.destroy', $industry) }}" class="mt-4" onsubmit="return confirm('Delete this industry?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-sm text-red-500 underline">Delete this industry</button>
</form>
@endif

@endsection
