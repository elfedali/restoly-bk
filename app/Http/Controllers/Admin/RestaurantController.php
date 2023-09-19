<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewRestaurant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RestaurantStoreRequest;
use App\Http\Requests\Admin\RestaurantUpdateRequest;
use App\Jobs\SyncMedia;
use App\Mail\NewRestaurantMail;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): View
    {
        $restaurants = Restaurant::all();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function show(Request $request, Restaurant $restaurant): View
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function create(Request $request): View
    {
        return view('admin.restaurants.create');
    }

    public function store(RestaurantStoreRequest $request): RedirectResponse
    {
        $restaurant = Restaurant::create($request->validated());

        event(new NewRestaurant($restaurant));

        Mail::to($restaurant->email)->send(new NewRestaurantMail($restaurant));

        SyncMedia::dispatch($restaurant);

        $request->session()->flash('restaurant.name', $restaurant->name);

        return redirect()->route('admin.restaurants.index');
    }

    public function edit(Request $request, Restaurant $restaurant): View
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(RestaurantUpdateRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $restaurant->save();

        return redirect()->route('admin.restaurants.index');
    }

    public function destroy(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index');
    }
}
