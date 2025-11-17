<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
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
    
        if ($response->successful()) {
            $data = $response->json();
            $apiUser = $data['user'];
    
            // Create or update the local user
            

            $user = User::updateOrCreate(
                ['email' => $apiUser['email']],
                [
                    'name' => $apiUser['name'] ?? $apiUser['email'],
                    'password' => Hash::make('api-login')
                ]
            );
            
    
            // Log in user in Laravel
            Auth::login($user);
    
            // Store API token
            session(['api_token' => $apiUser['token']]);
    
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
