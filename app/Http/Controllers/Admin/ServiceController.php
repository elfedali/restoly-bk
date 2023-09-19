<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceStoreRequest;
use App\Http\Requests\Admin\ServiceUpdateRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::all();

        return view('admin.services.index', compact('services'));
    }

    public function show(Request $request, Service $service): View
    {
        return view('admin.services.show', compact('service'));
    }

    public function create(Request $request): View
    {
        return view('admin.services.create');
    }

    public function store(ServiceStoreRequest $request): RedirectResponse
    {
        $service = Service::create($request->validated());

        $request->session()->flash('service.name', $service->name);

        return redirect()->route('admin.services.index');
    }

    public function edit(Request $request, Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, Service $service): RedirectResponse
    {
        $service->save();

        return redirect()->route('admin.services.index');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index');
    }
}
