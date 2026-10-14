<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $roleSlug = Auth::user()->role_slug ?? 'superadmin';

            return redirect("/{$roleSlug}/dashboard");
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user is associated with a Laboratory facility
            $isLabUser = str_contains(strtolower($user->role ?? ''), 'lab') ||
                         str_contains(strtolower($user->role ?? ''), 'laboratory') ||
                         ($user->hospital && $user->hospital->medical_type === 'Laboratory');

            // Find associated hospital facility
            $hospital = $user->hospital ?? Hospital::where('email', $user->email)->first() ?? Hospital::where('name', $user->hospital_name)->first();

            if (! $isLabUser && $hospital && $hospital->medical_type === 'Laboratory') {
                $isLabUser = true;
            }

            // If it's a Laboratory facility, enforce enable_laboratory permission
            if ($isLabUser && $hospital && ! $hospital->enable_laboratory) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Laboratory access is currently disabled for this facility. Please contact the Super Admin to enable it in Admin Permissions.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $roleSlug = $user->role_slug ?? 'superadmin';

            return redirect()->intended("/{$roleSlug}/dashboard")
                ->with('success', 'Welcome back, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'hospital_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'hospital_name' => $validated['hospital_name'] ?? 'RK Hospital Care',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account registered successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Logged out successfully.');
    }
}
