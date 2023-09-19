<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageStoreRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = Page::all();

        return view('admin.pages.index', compact('pages'));
    }

    public function show(Request $request, Page $page): View
    {
        return view('admin.pages.show', compact('page'));
    }

    public function create(Request $request): View
    {
        return view('admin.pages.create');
    }

    public function store(PageStoreRequest $request): RedirectResponse
    {
        $page = Page::create($request->validated());

        $request->session()->flash('page.title', $page->title);

        return redirect()->route('admin.pages.index');
    }

    public function edit(Request $request, Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageUpdateRequest $request, Page $page): RedirectResponse
    {
        $page->save();

        return redirect()->route('admin.pages.index');
    }

    public function destroy(Request $request, Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index');
    }
}
