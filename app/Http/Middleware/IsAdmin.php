<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated first
        if (!Auth::check()) {
            // Session expired or not logged in - redirect to login with intended URL
            return redirect()->guest(route('login'))
                ->with('error', 'Sesi login Anda telah berakhir. Silakan login kembali.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Check if user is admin
        if (!$user->isAdmin()) {
            // Logged in but not admin - show 403
            abort(403, 'Hanya Administrator yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}