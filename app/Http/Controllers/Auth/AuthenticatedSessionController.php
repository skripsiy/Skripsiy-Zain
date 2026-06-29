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

        $intended = $request->session()->get('url.intended');
        if ($intended) {
            $ignoredPatterns = [
                '/work-session',
                '/notifications',
                '/filter-tickets',
                '/mark-read'
            ];
            foreach ($ignoredPatterns as $pattern) {
                if (str_contains($intended, $pattern)) {
                    $request->session()->forget('url.intended');
                    break;
                }
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAgent()) {
            $today = \Carbon\Carbon::today();
            $session = \App\Models\AgentWorkSession::where('user_id', $user->id)
                ->where('work_date', $today)
                ->first();

            if ($session && in_array($session->status, ['online', 'aux'])) {
                return redirect()->back()->withErrors([
                    'logout_blocked' => 'Anda harus mengakhiri shift (End Shift) terlebih dahulu sebelum logout.'
                ])->with('error', 'Anda harus mengakhiri shift (End Shift) terlebih dahulu sebelum logout.');
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
