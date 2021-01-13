<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ActivityUser
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
        if (Session::get('customer_id')) {
            $expiresAt = Carbon::now()->addMinutes(1);
            Cache::put('user' . Session::get('customer_id'), true, $expiresAt);
        }
        if (Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(1);
            Cache::put('admin' . Auth::user()->admin_id, true, $expiresAt);
        }
        return $next($request);
    }
}
