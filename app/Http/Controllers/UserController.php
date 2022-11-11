<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show Register/Create form
    public function create()
    {
        return view('users.register');
    }

    // Create New User
    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => 'required|min:6|confirmed',
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User Created and Logged In');
    }

    // Logout Usr
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'User Logged Out Successfully');
    }

    // Show Login Form
    public function login()
    {
        return view('users.login');
    }

    // authenticate User
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

}
