<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewContact;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactStoreRequest;
use App\Http\Requests\Admin\ContactUpdateRequest;
use App\Mail\NewContactMail;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $contacts = Contact::all();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Request $request, Contact $contact): View
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function create(Request $request): View
    {
        return view('admin.contacts.create');
    }

    public function store(ContactStoreRequest $request): RedirectResponse
    {
        $contact = Contact::create($request->validated());

        event(new NewContact($contact));

        Mail::to($contact->email)->send(new NewContactMail($contact));

        $request->session()->flash('contact.name', $contact->name);

        return redirect()->route('admin.contacts.index');
    }

    public function edit(Request $request, Contact $contact): View
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(ContactUpdateRequest $request, Contact $contact): RedirectResponse
    {
        $contact->save();

        return redirect()->route('admin.contacts.index');
    }

    public function destroy(Request $request, Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index');
    }
}
