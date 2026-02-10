<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Belum login
        if (!$user) {
            return redirect()->route('login');
        }

        // Role tidak sesuai
        if (! in_array($user->role, $roles)) {
            return redirect()->route($this->redirectByRole($user->role));
        }

        return $next($request);
    }

    private function redirectByRole(string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'petugas' => 'petugas.dashboard',
            'peminjam' => 'peminjam.dashboard',
            default => 'login',
        };
    }
}
