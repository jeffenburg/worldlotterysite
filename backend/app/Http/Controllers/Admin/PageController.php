<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $pages = Page::query()
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('title', 'ilike', "%{$search}%")
                ->orWhere('slug', 'ilike', "%{$search}%")))
            ->orderBy('title')
            ->paginate(25)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'search'));
    }

    public function create()
    {
        return view('admin.pages.create', ['page' => new Page(['active' => true])]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::create($request->validated());

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->validated());

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', "Page \"{$page->title}\" deleted.");
    }
}
