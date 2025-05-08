<?php
namespace App\Cores;
use Illuminate\Http\Request;
use Closure;

class Middleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}