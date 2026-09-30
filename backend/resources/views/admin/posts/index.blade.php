@extends('admin.layout')

@section('title', 'Posts')

@section('actions')
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">New post</a>
@endsection

@section('content')
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.posts.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search title or slug…">
            <button type="submit" class="btn">Search</button>
            @if ($search)
                <a href="{{ route('admin.posts.index') }}" class="btn">Clear</a>
            @endif
        </form>
        <span class="muted">{{ $posts->total() }} total</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Slug</th>
                <th>Published</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td style="width: 56px;">
                        @if ($post->image_url)
                            <img src="{{ $post->image_url }}" alt="" class="thumb">
                        @endif
                    </td>
                    <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                    <td class="mono muted">{{ $post->slug }}</td>
                    <td class="muted">{{ $post->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td><span class="badge {{ $post->active ? 'badge-on' : 'badge-off' }}">{{ $post->active ? 'Published' : 'Draft' }}</span></td>
                    <td class="actions">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete “{{ addslashes($post->title) }}”? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No posts found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $posts->links() }}</div>
@endsection
