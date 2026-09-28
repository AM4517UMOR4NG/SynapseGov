<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitReportSubmission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'report-submission:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terlalu banyak pengiriman laporan. Silakan coba lagi dalam '.$seconds.' detik.',
                ], 429);
            }

            return back()->with('error', 'Terlalu banyak pengiriman laporan. Silakan coba lagi dalam '.$seconds.' detik.');
        }

        RateLimiter::hit($key, 3600); // 1 hour window

        return $next($request);
    }
}
