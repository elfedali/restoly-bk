<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentStoreRequest;
use App\Http\Requests\Admin\PaymentUpdateRequest;
use App\Mail\NewPaymentMail;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::all();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Request $request, Payment $payment): View
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function create(Request $request): View
    {
        return view('admin.payments.create');
    }

    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        $payment = Payment::create($request->validated());

        event(new NewPayment($payment));

        Mail::to($payment->email)->send(new NewPaymentMail($payment));

        $request->session()->flash('payment.name', $payment->name);

        return redirect()->route('admin.payments.index');
    }

    public function edit(Request $request, Payment $payment): View
    {
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
    {
        $payment->save();

        return redirect()->route('admin.payments.index');
    }

    public function destroy(Request $request, Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index');
    }
}
