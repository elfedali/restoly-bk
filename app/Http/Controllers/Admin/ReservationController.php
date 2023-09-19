<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewReservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationStoreRequest;
use App\Http\Requests\Admin\ReservationUpdateRequest;
use App\Mail\NewReservationMail;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = Reservation::all();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Request $request, Reservation $reservation): View
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    public function create(Request $request): View
    {
        return view('admin.reservations.create');
    }

    public function store(ReservationStoreRequest $request): RedirectResponse
    {
        $reservation = Reservation::create($request->validated());

        event(new NewReservation($reservation));

        Mail::to($reservation->email)->send(new NewReservationMail($reservation));

        $request->session()->flash('reservation.name', $reservation->name);

        return redirect()->route('admin.reservations.index');
    }

    public function edit(Request $request, Reservation $reservation): View
    {
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(ReservationUpdateRequest $request, Reservation $reservation): RedirectResponse
    {
        $reservation->save();

        return redirect()->route('admin.reservations.index');
    }

    public function destroy(Request $request, Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('admin.reservations.index');
    }
}
