<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuStoreRequest;
use App\Http\Requests\Admin\MenuUpdateRequest;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $menus = Menu::all();

        return view('admin.menus.index', compact('menus'));
    }

    public function show(Request $request, Menu $menu): View
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function create(Request $request): View
    {
        return view('admin.menus.create');
    }

    public function store(MenuStoreRequest $request): RedirectResponse
    {
        $menu = Menu::create($request->validated());

        $request->session()->flash('menu.name', $menu->name);

        return redirect()->route('admin.menus.index');
    }

    public function edit(Request $request, Menu $menu): View
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(MenuUpdateRequest $request, Menu $menu): RedirectResponse
    {
        $menu->save();

        return redirect()->route('admin.menus.index');
    }

    public function destroy(Request $request, Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menus.index');
    }
}
