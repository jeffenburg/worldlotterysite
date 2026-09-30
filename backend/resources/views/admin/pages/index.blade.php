@extends('admin.layout')

@section('title', 'Pages')

@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">New page</a>
@endsection

@section('content')
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.pages.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search title or slug…">
            <button type="submit" class="btn">Search</button>
            @if ($search)
                <a href="{{ route('admin.pages.index') }}" class="btn">Clear</a>
            @endif
        </form>
        <span class="muted">{{ $pages->total() }} total</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Type</th>
                <th>SEO title</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($pages as $page)
                <tr>
                    <td><a href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a></td>
                    <td class="mono muted">{{ $page->slug }}</td>
                    <td><span class="badge badge-off">{{ $page->page_type }}</span></td>
                    <td class="muted">{{ $page->meta_title ?? '—' }}</td>
                    <td><span class="badge {{ $page->active ? 'badge-on' : 'badge-off' }}">{{ $page->active ? 'Published' : 'Draft' }}</span></td>
                    <td class="muted">{{ $page->updated_at?->format('Y-m-d') }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete “{{ addslashes($page->title) }}”? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">No pages found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $pages->links() }}</div>
@endsection
