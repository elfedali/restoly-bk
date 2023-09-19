<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function show(Request $request, User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function create(Request $request): View
    {
        return view('admin.users.create');
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        event(new NewUser($user));

        Mail::to($user->email)->send(new NewUserMail($user));

        $request->session()->flash('user.name', $user->name);

        return redirect()->route('admin.users.index');
    }

    public function edit(Request $request, User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
