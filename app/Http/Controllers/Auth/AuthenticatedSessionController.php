<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;


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
            $response = Http::api()->post('/users/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful())
            {
                $data = $response->json();

                $user = $data['user'];
                //$token = $user['token'];
                
                session([
                    'api_token' => $user['token'],
                    'user_email' => $user['email'],
                    'user_name' => $user['name'],
                ]);
                return redirect()->route('dashboard');
            }
            
        return back()->withErrors(['email' => 'Login failed']);
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        session()->forget('api_token');

        return redirect('/');
    }
}
