<?php
namespace App\Middlewares;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() == 'POST') {
            echo 'error';
            return;
        }

        echo 'authenticated';
        return $next($request);
    }
}