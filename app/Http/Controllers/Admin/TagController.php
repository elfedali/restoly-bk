<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagStoreRequest;
use App\Http\Requests\Admin\TagUpdateRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tags = Tag::all();

        return view('admin.tags.index', compact('tags'));
    }

    public function show(Request $request, Tag $tag): View
    {
        return view('admin.tags.show', compact('tag'));
    }

    public function create(Request $request): View
    {
        return view('admin.tags.create');
    }

    public function store(TagStoreRequest $request): RedirectResponse
    {
        $tag = Tag::create($request->validated());

        $request->session()->flash('tag.name', $tag->name);

        return redirect()->route('admin.tags.index');
    }

    public function edit(Request $request, Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(TagUpdateRequest $request, Tag $tag): RedirectResponse
    {
        $tag->save();

        return redirect()->route('admin.tags.index');
    }

    public function destroy(Request $request, Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index');
    }
}
