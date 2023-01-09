<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\Request;

class AccessToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('access_token')) {
            abort(403,'Forbidden, access token not found in the request');
        }

        if ($request['access_token'] !== env('ACCESS_TOKEN')) {
            abort(401, 'Invalid access token');
        }

        return $next($request);
    }
}
