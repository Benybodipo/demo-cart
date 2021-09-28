<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Key;

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
        $api_key = Key::where('key', $key)->get();

        if (count($api_key))
        {
            if (getenv('DEMO_API_KEY') != $api_key[0]->key)
                return redirect()->route('request-api-key');
        }
        else
        {
            return redirect()->route('request-api-key');
        }
        return $next($request);
    }
}
