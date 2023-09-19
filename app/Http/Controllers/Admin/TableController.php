<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TableStoreRequest;
use App\Http\Requests\Admin\TableUpdateRequest;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(Request $request): View
    {
        $tables = Table::all();

        return view('admin.tables.index', compact('tables'));
    }

    public function show(Request $request, Table $table): View
    {
        return view('admin.tables.show', compact('table'));
    }

    public function create(Request $request): View
    {
        return view('admin.tables.create');
    }

    public function store(TableStoreRequest $request): RedirectResponse
    {
        $table = Table::create($request->validated());

        $request->session()->flash('table.name', $table->name);

        return redirect()->route('admin.tables.index');
    }

    public function edit(Request $request, Table $table): View
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(TableUpdateRequest $request, Table $table): RedirectResponse
    {
        $table->save();

        return redirect()->route('admin.tables.index');
    }

    public function destroy(Request $request, Table $table): RedirectResponse
    {
        $table->delete();

        return redirect()->route('admin.tables.index');
    }
}
