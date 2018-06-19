<?php

namespace Inggo\Boilerplate\Http\Middleware;

use Closure;

class RequiresJson
{
    /**
     * We only accept json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader != 'application/json') {
            return response()->json([], 400);
        }

        return $next($request);
    }
}
