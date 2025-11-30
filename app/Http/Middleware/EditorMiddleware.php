<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EditorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \App\Models\User|null $user
         */
        $user = Auth::user();

        if (
            $user &&
            (method_exists($user, 'isEditor') && $user->isEditor()) ||
            (!method_exists($user, 'isEditor') && $user->role === 'editor')

        ) {
            return $next($request);

        }

        // redirect homepage
        return redirect()->route('home');
    }
}
