<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryStoreRequest;
use App\Http\Requests\Admin\CountryUpdateRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $countries = Country::all();

        return view('admin.countries.index', compact('countries'));
    }

    public function show(Request $request, Country $country): View
    {
        return view('admin.countries.show', compact('country'));
    }

    public function create(Request $request): View
    {
        return view('admin.countries.create');
    }

    public function store(CountryStoreRequest $request): RedirectResponse
    {

        $country = Country::create($request->validated());

        return redirect()->route('admin.countries.index');
    }

    public function edit(Request $request, Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryUpdateRequest $request, Country $country): RedirectResponse
    {
        $country->update($request->validated());
        // is_active is a checkbox, if not checked, it won't be sent in the request
        $country->is_active = $request->has('is_active');

        $country->save();

        return redirect()->route('admin.countries.index');
    }

    public function destroy(Request $request, Country $country): RedirectResponse
    {
        $country->delete();

        return redirect()->route('admin.countries.index');
    }
}
