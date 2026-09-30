@csrf
<div class="form-grid">
    <div class="card">
        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required @class(['is-invalid' => $errors->has('title')])>
            @error('title') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" @class(['mono', 'is-invalid' => $errors->has('slug')])>
            <div class="hint">Leave blank to generate from the title. Used in the public URL.</div>
            @error('slug') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div class="field">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="tall rich-editor">{{ old('content', $post->content) }}</textarea>
        </div>
    </div>

    <div>
        <div class="card">
            <h2>Publishing</h2>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="active" value="1" @checked(old('active', $post->active))>
                    Published (visible on the public site)
                </label>
            </div>
            <div class="field">
                <label for="published_at">Publish date</label>
                <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" @class(['is-invalid' => $errors->has('published_at')])>
                @error('published_at') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="card">
            <h2>Featured image</h2>
            @if ($post->image_url)
                <img src="{{ $post->image_url }}" alt="" class="image-preview">
                <div class="field">
                    <label class="checkbox">
                        <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))>
                        Remove current image
                    </label>
                </div>
            @endif
            <div class="field">
                <label for="image">{{ $post->image_url ? 'Replace image' : 'Upload image' }}</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" @disabled(! $storageConfigured) @class(['is-invalid' => $errors->has('image')])>
                @if ($storageConfigured)
                    <div class="hint">JPG, PNG, WebP or GIF up to 5 MB. Stored in Supabase Storage.</div>
                @else
                    <div class="error">Supabase Storage is not configured (SUPABASE_URL / SUPABASE_SERVICE_KEY / SUPABASE_STORAGE_BUCKET).</div>
                @endif
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="card">
            <h2>SEO</h2>
            <div class="field">
                <label for="meta_title">SEO title</label>
                <input type="text" id="meta_title" name="meta_title" maxlength="255" value="{{ old('meta_title', $post->meta_title) }}">
            </div>
            <div class="field">
                <label for="meta_description">SEO description</label>
                <textarea id="meta_description" name="meta_description">{{ old('meta_description', $post->meta_description) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.posts.index') }}" class="btn">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $post->exists ? 'Save changes' : 'Create post' }}</button>
</div>

@push('scripts')
    @include('admin.partials.tinymce')
@endpush
