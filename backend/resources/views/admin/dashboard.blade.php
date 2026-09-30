@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="stats">
        <div class="stat">
            <div class="label">Lotteries</div>
            <div class="value">{{ $counts['lotteries'] }}</div>
            <div class="muted">{{ $counts['activeLotteries'] }} active</div>
        </div>
        <div class="stat">
            <div class="label">Pages</div>
            <div class="value">{{ $counts['pages'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Posts</div>
            <div class="value">{{ $counts['posts'] }}</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <h2>Recently updated posts</h2>
            @if ($recentPosts->isEmpty())
                <p class="muted">No posts yet. <a href="{{ route('admin.posts.create') }}">Create one</a>.</p>
            @else
                <table>
                    <tbody>
                    @foreach ($recentPosts as $post)
                        <tr>
                            <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                            <td class="muted">{{ $post->updated_at?->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h2>Recently updated lotteries</h2>
            <table>
                <tbody>
                @foreach ($recentLotteries as $lottery)
                    <tr>
                        <td><a href="{{ route('admin.lotteries.edit', $lottery) }}">{{ $lottery->name }}</a></td>
                        <td class="muted">{{ $lottery->updated_at?->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
