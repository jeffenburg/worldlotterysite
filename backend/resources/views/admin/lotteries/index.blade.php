@extends('admin.layout')

@section('title', 'Lotteries')

@section('actions')
    <a href="{{ route('admin.lotteries.create') }}" class="btn btn-primary">New lottery</a>
@endsection

@section('content')
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.lotteries.index') }}">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search name, country, slug…">
            <button type="submit" class="btn">Search</button>
            @if ($search)
                <a href="{{ route('admin.lotteries.index') }}" class="btn">Clear</a>
            @endif
        </form>
        <span class="muted">{{ $lotteries->total() }} total</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Country</th>
                <th>Jackpot</th>
                <th>Next draw</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($lotteries as $lottery)
                <tr>
                    <td><a href="{{ route('admin.lotteries.edit', $lottery) }}">{{ $lottery->name }}</a></td>
                    <td class="mono muted">{{ $lottery->slug }}</td>
                    <td>{{ $lottery->country ?? '—' }}</td>
                    <td>
                        @if ($lottery->jackpot !== null)
                            {{ $lottery->jackpot_currency }} {{ number_format((float) $lottery->jackpot) }}
                        @else
                            <span class="muted">—</span>
                        @endif
                    </td>
                    <td class="muted">{{ $lottery->next_draw_at?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td><span class="badge {{ $lottery->active ? 'badge-on' : 'badge-off' }}">{{ $lottery->active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="actions">
                        <a href="{{ route('admin.lotteries.edit', $lottery) }}" class="btn btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.lotteries.destroy', $lottery) }}" onsubmit="return confirm('Delete “{{ addslashes($lottery->name) }}”? This also removes its draw history and cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">No lotteries found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $lotteries->links() }}</div>
@endsection
