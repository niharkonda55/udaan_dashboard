<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect to role-specific dashboard
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCameraman()) {
            return redirect()->route('cameraman.dashboard');
        } elseif ($user->isEditor()) {
            return redirect()->route('editor.dashboard');
        }

        return redirect()->route('login');
    }
}

