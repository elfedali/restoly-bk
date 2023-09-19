<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuCategoryStoreRequest;
use App\Http\Requests\Admin\MenuCategoryUpdateRequest;
use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $menuCategories = MenuCategory::all();

        return view('admin.menu_categories.index', compact('menu_categories'));
    }

    public function show(Request $request, MenuCategory $menuCategory): View
    {
        return view('admin.menu_categories.show', compact('menu_category'));
    }

    public function create(Request $request): View
    {
        return view('admin.menu_categories.create');
    }

    public function store(MenuCategoryStoreRequest $request): RedirectResponse
    {
        $menuCategory = MenuCategory::create($request->validated());

        $request->session()->flash('menu_category.name', $menu_category->name);

        return redirect()->route('admin.menu_categories.index');
    }

    public function edit(Request $request, MenuCategory $menuCategory): View
    {
        return view('admin.menu_categories.edit', compact('menu_category'));
    }

    public function update(MenuCategoryUpdateRequest $request, MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->save();

        return redirect()->route('admin.menu_categories.index');
    }

    public function destroy(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->delete();

        return redirect()->route('admin.menu_categories.index');
    }
}
