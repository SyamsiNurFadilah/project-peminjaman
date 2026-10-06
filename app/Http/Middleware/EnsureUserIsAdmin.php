<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses terlarang. Anda harus login sebagai admin.',
                ], 403);
            }

            return redirect()->route('catalog.index')->with('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh Administrator / Petugas.');
        }

        return $next($request);
    }
}
