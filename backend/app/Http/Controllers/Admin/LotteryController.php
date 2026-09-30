<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LotteryRequest;
use App\Models\Lottery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $lotteries = Lottery::query()
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'ilike', "%{$search}%")
                ->orWhere('country', 'ilike', "%{$search}%")
                ->orWhere('slug', 'ilike', "%{$search}%")))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.lotteries.index', compact('lotteries', 'search'));
    }

    public function create()
    {
        return view('admin.lotteries.create', ['lottery' => new Lottery(['active' => true])]);
    }

    public function store(LotteryRequest $request): RedirectResponse
    {
        $lottery = Lottery::create($request->validatedData());

        return redirect()->route('admin.lotteries.edit', $lottery)->with('success', 'Lottery created.');
    }

    public function edit(Lottery $lottery)
    {
        return view('admin.lotteries.edit', compact('lottery'));
    }

    public function update(LotteryRequest $request, Lottery $lottery): RedirectResponse
    {
        $lottery->update($request->validatedData());

        return redirect()->route('admin.lotteries.edit', $lottery)->with('success', 'Lottery updated.');
    }

    public function destroy(Lottery $lottery): RedirectResponse
    {
        $lottery->delete();

        return redirect()->route('admin.lotteries.index')->with('success', "Lottery \"{$lottery->name}\" deleted.");
    }
}
