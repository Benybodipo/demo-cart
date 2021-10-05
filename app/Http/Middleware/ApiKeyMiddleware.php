<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Key;
use Illuminate\Support\Facades\Cookie;

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
        $key = Cookie::get('DEMO_API_KEY');
        $api_key = Key::where('key', $key)->get();

        if (!count($api_key))
        {
            return redirect()->route('request-api-key');
        }
        
        return $next($request);
    }
}
