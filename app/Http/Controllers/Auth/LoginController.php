<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login
     */
    protected function authenticated(Request $request, $user)
    {
        // =========================
        // SUPER ADMIN
        // =========================
        if ($user->role == 'super_admin') {

            return redirect()->route(
                'superadmin.users.index'
            );

        }
        // =========================
// DIREKSI & MANAGER
// =========================
        if (in_array($user->role, ['direksi', 'manager'])) {

            return redirect()->route(
                'manager.dashboard'
            );

        }

        // =========================
        // STAFF
        // =========================
        if ($user->role == 'staff') {

            return redirect()->route(
                'staff.project.index'
            );

        }

        return redirect('/');
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->middleware('auth')->only('logout');
    }
}
