<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTicketManager
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || (! $user->isAdmin() && ! $user->isTicketManager())) {
            abort(403, 'Ticket Manager access required.');
        }

        return $next($request);
    }
}
