<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrderProcessor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || (! $user->isAdmin() && ! $user->isOrderProcessor())) {
            abort(403, 'Order Processor access required.');
        }

        return $next($request);
    }
}
