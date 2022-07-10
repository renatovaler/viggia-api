<?php

namespace App\Structure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Structure\Exceptions\OnlyAcceptJsonException;

class OnlyAcceptJsonMiddleware
{
    /**
     * We only accept json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->is('auth/*') && ! $request->expectsJson()) {
            throw new OnlyAcceptJsonException();
        }
        return $next($request);
    }
}
