<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewPost;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostStoreRequest;
use App\Http\Requests\Admin\PostUpdateRequest;
use App\Mail\NewPostMail;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Request $request, Post $post): View
    {
        return view('admin.posts.show', compact('post'));
    }

    public function create(Request $request): View
    {
        return view('admin.posts.create');
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $post = Post::create($request->validated());

        event(new NewPost($post));

        Mail::to($post->author)->send(new NewPostMail($post));

        $request->session()->flash('post.title', $post->title);

        return redirect()->route('admin.posts.index');
    }

    public function edit(Request $request, Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(PostUpdateRequest $request, Post $post): RedirectResponse
    {
        $post->save();

        return redirect()->route('admin.posts.index');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index');
    }
}
