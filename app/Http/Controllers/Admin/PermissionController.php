<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionStoreRequest;
use App\Http\Requests\Admin\PermissionUpdateRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $permissions = Permission::all();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function show(Request $request, Permission $permission): View
    {
        return view('admin.permissions.show', compact('permission'));
    }

    public function create(Request $request): View
    {
        return view('admin.permissions.create');
    }

    public function store(PermissionStoreRequest $request): RedirectResponse
    {
        $permission = Permission::create($request->validated());

        $request->session()->flash('permission.name', $permission->name);

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Request $request, Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(PermissionUpdateRequest $request, Permission $permission): RedirectResponse
    {
        $permission->save();

        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Request $request, Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index');
    }
}
