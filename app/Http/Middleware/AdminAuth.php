<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->check() && $request->user()->role_id !== User::ROLE_ID_ADMIN) {
            $message = ['message' => 'Permission Denied'];

            return response($message, 401);
        }

        return $next($request);
    }
}
