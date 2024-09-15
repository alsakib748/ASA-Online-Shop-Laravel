<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $url = '';
        $notification = array();

        // Redirect the previous url  
        // if (session()->has('url.intended')) {
        //     return redirect(session()->get('url.intended')); // Redirect to the intended URL
        // }

        if ($request->user()->role === 'admin') {
            // $url = 'admin.dashboard';
            $url = 'admin.dashboard';
            $notification = array(
                'message' => 'User Admin Login Successfully',
                'alert-type' => 'info'
            );
        } else if ($request->user()->role === 'user') {
            $url = 'dashboard';
            $notification = array(
                'message' => 'User Login Successfully',
                'alert-type' => 'info'
            );
        }

        return redirect()->intended(route($url, absolute: false))->with($notification);
        // return redirect()->intended($url)->with($notification);

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}