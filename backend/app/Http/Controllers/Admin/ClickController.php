<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClickController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $clicks = LinkClick::query()
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('slug', 'ilike', "%{$search}%")
                ->orWhere('placement', 'ilike', "%{$search}%")
                ->orWhere('country', 'ilike', "%{$search}%")))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        $stats = [
            'today' => LinkClick::where('created_at', '>=', now()->startOfDay())->count(),
            'week' => LinkClick::where('created_at', '>=', now()->subDays(7))->count(),
            'total' => LinkClick::count(),
        ];

        $topSlugs = LinkClick::query()
            ->select('slug', DB::raw('count(*) as clicks'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('slug')
            ->orderByDesc('clicks')
            ->limit(8)
            ->get();

        return view('admin.clicks.index', compact('clicks', 'search', 'stats', 'topSlugs'));
    }
}
