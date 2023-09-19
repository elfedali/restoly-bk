<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleStoreRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function show(Request $request, Role $role): View
    {
        return view('admin.roles.show', compact('role'));
    }

    public function create(Request $request): View
    {
        return view('admin.roles.create');
    }

    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $role = Role::create($request->validated());

        $request->session()->flash('role.name', $role->name);

        return redirect()->route('admin.roles.index');
    }

    public function edit(Request $request, Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $role->save();

        return redirect()->route('admin.roles.index');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index');
    }
}
