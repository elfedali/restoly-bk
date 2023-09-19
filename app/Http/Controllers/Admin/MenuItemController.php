<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItemStoreRequest;
use App\Http\Requests\Admin\MenuItemUpdateRequest;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(Request $request): View
    {
        $menuItems = MenuItem::all();

        return view('admin.menu_items.index', compact('menu_items'));
    }

    public function show(Request $request, MenuItem $menuItem): View
    {
        return view('admin.menu_items.show', compact('menu_item'));
    }

    public function create(Request $request): View
    {
        return view('admin.menu_items.create');
    }

    public function store(MenuItemStoreRequest $request): RedirectResponse
    {
        $menuItem = MenuItem::create($request->validated());

        $request->session()->flash('menu_item.name', $menu_item->name);

        return redirect()->route('admin.menu_items.index');
    }

    public function edit(Request $request, MenuItem $menuItem): View
    {
        return view('admin.menu_items.edit', compact('menu_item'));
    }

    public function update(MenuItemUpdateRequest $request, MenuItem $menuItem): RedirectResponse
    {
        $menuItem->save();

        return redirect()->route('admin.menu_items.index');
    }

    public function destroy(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('admin.menu_items.index');
    }
}
