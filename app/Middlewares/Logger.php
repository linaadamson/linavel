<?php
namespace App\Middlewares;

use Closure;

class Logger
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;

        echo "Request took " . round($duration * 1000, 2) . " ms to complete.\n";

        return $response;
    }
}