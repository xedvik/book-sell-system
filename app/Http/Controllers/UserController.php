<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::getFilteredUsers($request->search);

        return view('a-panel.users.index', compact('users'));
    }


    // public function create()
    // {
    //     return view('a-panel.users.create');
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return redirect()->route('a-panel.users')->with('success', 'Пользователь успешно создан');
    // }


    // public function show(User $user)
    // {
    //     $user->loadCount('purchases');
    //     return view('a-panel.users.show', compact('user'));
    // }


    // public function edit(User $user)
    // {
    //     return view('a-panel.users.edit', compact('user'));
    // }


    // public function update(Request $request, User $user)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
    //     ]);

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //     ]);

    //     if ($request->filled('password')) {
    //         $request->validate([
    //             'password' => ['confirmed', Rules\Password::defaults()],
    //         ]);

    //         $user->update([
    //             'password' => Hash::make($request->password),
    //         ]);
    //     }

    //     return redirect()->route('a-panel.users')->with('success', 'Пользователь успешно обновлен');
    // }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('a-panel.users')->with('success', 'Пользователь успешно удален');
    }
}
