<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->api_key;

        if ($key != getenv('DEMO_API_KEY'))
        {
            // return response()->json(['message' => 'API Key not found'], 401);
            return redirect()->route('request-api-key');
        }
        return $next($request);
    }
}
