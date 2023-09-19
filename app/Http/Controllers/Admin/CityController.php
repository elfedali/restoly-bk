<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityStoreRequest;
use App\Http\Requests\Admin\CityUpdateRequest;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $cities = City::all();

        return view('admin.cities.index', compact('cities'));
    }

    public function show(Request $request, City $city): View
    {
        return view('admin.cities.show', compact('city'));
    }

    public function create(Request $request): View
    {
        return view('admin.cities.create');
    }

    public function store(CityStoreRequest $request): RedirectResponse
    {
        $city = City::create($request->validated());

        $request->session()->flash('city.name', $city->name);

        return redirect()->route('admin.cities.index');
    }

    public function edit(Request $request, City $city): View
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(CityUpdateRequest $request, City $city): RedirectResponse
    {
        $city->save();

        return redirect()->route('admin.cities.index');
    }

    public function destroy(Request $request, City $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('admin.cities.index');
    }
}
