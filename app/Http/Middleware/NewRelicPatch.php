<?php

namespace App\Http\Middleware;

use Closure;

class NewRelicPatch
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        event('router.filter:controller:newrelic-patch', [$request, $response], true);

        return $response;
    }
}