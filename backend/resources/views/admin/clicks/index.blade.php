@extends('admin.layout')

@section('title', 'Link clicks')

@section('content')
    <div class="stats">
        <div class="stat">
            <div class="label">Today</div>
            <div class="value">{{ number_format($stats['today']) }}</div>
        </div>
        <div class="stat">
            <div class="label">Last 7 days</div>
            <div class="value">{{ number_format($stats['week']) }}</div>
        </div>
        <div class="stat">
            <div class="label">All time</div>
            <div class="value">{{ number_format($stats['total']) }}</div>
        </div>
        @if ($topSlugs->isNotEmpty())
            <div class="stat">
                <div class="label">Top links (30 days)</div>
                <div class="muted" style="margin-top: 6px; font-size: 13px;">
                    @foreach ($topSlugs as $row)
                        <div><span class="mono">{{ $row->slug }}</span> · {{ number_format($row->clicks) }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="toolbar">
        <form method="GET" action="{{ route('admin.clicks.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search slug, placement, country…">
            <button type="submit" class="btn">Search</button>
            @if ($search)
                <a href="{{ route('admin.clicks.index') }}" class="btn">Clear</a>
            @endif
        </form>
        <span class="muted">{{ number_format($clicks->total()) }} matching</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>When</th>
                <th>Link</th>
                <th>Placement</th>
                <th>Location</th>
                <th>IP</th>
                <th>User agent</th>
                <th>Referer</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($clicks as $click)
                <tr>
                    <td class="muted" style="white-space: nowrap;" title="{{ $click->created_at }}">{{ $click->created_at->format('Y-m-d H:i:s') }}</td>
                    <td><a href="{{ $click->target_url }}" target="_blank" rel="noopener" class="mono">{{ $click->slug }}</a></td>
                    <td class="mono muted">{{ $click->placement ?? '—' }}</td>
                    <td>{{ collect([$click->city, $click->region, $click->country])->filter()->implode(', ') ?: '—' }}</td>
                    <td class="mono muted">{{ $click->ip ?? '—' }}</td>
                    <td class="muted" style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $click->user_agent }}">{{ $click->user_agent ?? '—' }}</td>
                    <td class="muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $click->referer }}">{{ $click->referer ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">No clicks recorded yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $clicks->links() }}</div>
@endsection
