<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        $testUsers = [];
        if (app()->environment('local', 'testing')) {
            $testUsers = User::with('roles')->get()->map(fn($u) => [
                'email' => $u->email,
                'name' => $u->name,
                'role' => $u->roles->first()?->name ?? 'none',
            ])->toArray();
        }
        return view('auth.login', compact('testUsers'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function quickLogin(Request $request): RedirectResponse
    {
        abort_unless(app()->environment('local', 'testing'), 404);
        $request->validate(['user_email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->user_email)->firstOrFail();
        Auth::login($user);
        $request->session()->regenerate();
        if ($user->hasRole('supplier')) {
            return redirect()->route('supplier.dashboard');
        }
        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
