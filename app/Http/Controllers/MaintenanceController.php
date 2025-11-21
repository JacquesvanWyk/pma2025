<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function showLoginForm()
    {
        if (! app()->isDownForMaintenance()) {
            return redirect('/');
        }

        return view('maintenance.login');
    }

    public function authenticate(Request $request)
    {
        if (! app()->isDownForMaintenance()) {
            return redirect('/');
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $password = config('app.maintenance_password', 'pma2024');

        if ($request->password === $password) {
            session()->put('maintenance_bypass', true);
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Invalid password.',
        ]);
    }

    public function logout()
    {
        session()->forget('maintenance_bypass');
        return redirect()->route('maintenance.login');
    }
}
