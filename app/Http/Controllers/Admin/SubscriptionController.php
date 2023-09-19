<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewSubscription;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionStoreRequest;
use App\Http\Requests\Admin\SubscriptionUpdateRequest;
use App\Mail\NewSubscriptionMail;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $subscriptions = Subscription::all();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Request $request, Subscription $subscription): View
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function create(Request $request): View
    {
        return view('admin.subscriptions.create');
    }

    public function store(SubscriptionStoreRequest $request): RedirectResponse
    {
        $subscription = Subscription::create($request->validated());

        event(new NewSubscription($subscription));

        Mail::to($subscription->email)->send(new NewSubscriptionMail($subscription));

        $request->session()->flash('subscription.name', $subscription->name);

        return redirect()->route('admin.subscriptions.index');
    }

    public function edit(Request $request, Subscription $subscription): View
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(SubscriptionUpdateRequest $request, Subscription $subscription): RedirectResponse
    {
        $subscription->save();

        return redirect()->route('admin.subscriptions.index');
    }

    public function destroy(Request $request, Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index');
    }
}
