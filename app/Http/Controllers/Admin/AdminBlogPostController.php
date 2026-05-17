<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class AdminBlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'adsterra_link' => 'nullable|url|max:500',
        ]);

        BlogPost::create($request->only(['title', 'content', 'adsterra_link']));

        return redirect()->route('admin.posts.index')->with('success', 'Central blog post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'adsterra_link' => 'nullable|url|max:500',
        ]);

        $post->update($request->only(['title', 'content', 'adsterra_link']));

        return redirect()->route('admin.posts.index')->with('success', 'Central blog post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return back()->with('success', 'Central blog post deleted successfully.');
    }
}
