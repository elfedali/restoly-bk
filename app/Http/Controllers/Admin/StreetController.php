<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StreetStoreRequest;
use App\Http\Requests\Admin\StreetUpdateRequest;
use App\Models\Street;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StreetController extends Controller
{
    public function index(Request $request): View
    {
        $streets = Street::all();

        return view('admin.streets.index', compact('streets'));
    }

    public function show(Request $request, Street $street): View
    {
        return view('admin.streets.show', compact('street'));
    }

    public function create(Request $request): View
    {
        return view('admin.streets.create');
    }

    public function store(StreetStoreRequest $request): RedirectResponse
    {
        $street = Street::create($request->validated());

        $request->session()->flash('street.name', $street->name);

        return redirect()->route('admin.streets.index');
    }

    public function edit(Request $request, Street $street): View
    {
        return view('admin.streets.edit', compact('street'));
    }

    public function update(StreetUpdateRequest $request, Street $street): RedirectResponse
    {
        $street->save();

        return redirect()->route('admin.streets.index');
    }

    public function destroy(Request $request, Street $street): RedirectResponse
    {
        $street->delete();

        return redirect()->route('admin.streets.index');
    }
}
