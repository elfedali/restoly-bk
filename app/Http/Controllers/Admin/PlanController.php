<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanStoreRequest;
use App\Http\Requests\Admin\PlanUpdateRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = Plan::all();

        return view('admin.plans.index', compact('plans'));
    }

    public function show(Request $request, Plan $plan): View
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function create(Request $request): View
    {
        return view('admin.plans.create');
    }

    public function store(PlanStoreRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());

        $request->session()->flash('plan.name', $plan->name);

        return redirect()->route('admin.plans.index');
    }

    public function edit(Request $request, Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(PlanUpdateRequest $request, Plan $plan): RedirectResponse
    {
        $plan->save();

        return redirect()->route('admin.plans.index');
    }

    public function destroy(Request $request, Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('admin.plans.index');
    }
}
