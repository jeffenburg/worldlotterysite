@csrf
<div class="form-grid">
    <div class="card">
        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required @class(['is-invalid' => $errors->has('title')])>
            @error('title') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" @class(['mono', 'is-invalid' => $errors->has('slug')])>
            <div class="hint">Leave blank to generate from the title. Used in the public URL.</div>
            @error('slug') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt">{{ old('excerpt', $page->excerpt) }}</textarea>
        </div>

        <div class="field">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="tall rich-editor">{{ old('content', $page->content) }}</textarea>
        </div>
    </div>

    <div>
        <div class="card">
            <h2>Status</h2>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="active" value="1" @checked(old('active', $page->active))>
                    Published (visible on the public site)
                </label>
            </div>
            <div class="field">
                <label for="page_type">Page type</label>
                <select id="page_type" name="page_type" @class(['is-invalid' => $errors->has('page_type')])>
                    @foreach (\App\Models\Page::TYPES as $value => $label)
                        <option value="{{ $value }}" @selected(old('page_type', $page->page_type ?? \App\Models\Page::TYPE_GUIDE) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="hint">Only guides are listed on /guides and the homepage. Lottery and homepage content is rendered in place and hidden from listings.</div>
                @error('page_type') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="card">
            <h2>SEO</h2>
            <div class="field">
                <label for="meta_title">SEO title</label>
                <input type="text" id="meta_title" name="meta_title" maxlength="255" value="{{ old('meta_title', $page->meta_title) }}">
            </div>
            <div class="field">
                <label for="meta_description">SEO description</label>
                <textarea id="meta_description" name="meta_description">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.pages.index') }}" class="btn">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $page->exists ? 'Save changes' : 'Create page' }}</button>
</div>

@push('scripts')
    @include('admin.partials.tinymce')
@endpush
