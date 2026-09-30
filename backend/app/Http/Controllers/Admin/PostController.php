<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Services\SupabaseStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PostController extends Controller
{
    public function __construct(private SupabaseStorage $storage) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $posts = Post::query()
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('title', 'ilike', "%{$search}%")
                ->orWhere('slug', 'ilike', "%{$search}%")))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search'));
    }

    public function create()
    {
        return view('admin.posts.create', [
            'post' => new Post(['active' => true, 'published_at' => now()]),
            'storageConfigured' => $this->storage->isConfigured(),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        try {
            if ($request->hasFile('image')) {
                $data['image_url'] = $this->storage->upload($request->file('image'), 'posts');
            }
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $post = Post::create($data);

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'storageConfigured' => $this->storage->isConfigured(),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $oldImage = $post->image_url;

        try {
            if ($request->hasFile('image')) {
                $data['image_url'] = $this->storage->upload($request->file('image'), 'posts');
            } elseif ($request->boolean('remove_image')) {
                $data['image_url'] = null;
            }
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $post->update($data);

        if ($oldImage && $post->image_url !== $oldImage) {
            $this->storage->delete($oldImage);
        }

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        $this->storage->delete($post->image_url);

        return redirect()->route('admin.posts.index')->with('success', "Post \"{$post->title}\" deleted.");
    }
}
