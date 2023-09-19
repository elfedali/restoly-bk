<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewKitchen;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KitchenStoreRequest;
use App\Http\Requests\Admin\KitchenUpdateRequest;
use App\Models\Kitchen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(Request $request): View
    {
        $kitchens = Kitchen::all();

        return view('admin.kitchens.index', compact('kitchens'));
    }

    public function show(Request $request, Kitchen $kitchen): View
    {
        return view('admin.kitchens.show', compact('kitchen'));
    }

    public function create(Request $request): View
    {
        return view('admin.kitchens.create');
    }

    public function store(KitchenStoreRequest $request): RedirectResponse
    {
        $kitchen = Kitchen::create($request->validated());

        event(new NewKitchen($kitchen));

        return redirect()->route('admin.kitchens.index');
    }

    public function edit(Request $request, Kitchen $kitchen): View
    {
        return view('admin.kitchens.edit', compact('kitchen'));
    }

    public function update(KitchenUpdateRequest $request, Kitchen $kitchen): RedirectResponse
    {
        $kitchen->save();

        return redirect()->route('admin.kitchens.index');
    }

    public function destroy(Request $request, Kitchen $kitchen): RedirectResponse
    {
        $kitchen->delete();

        return redirect()->route('admin.kitchens.index');
    }
}
