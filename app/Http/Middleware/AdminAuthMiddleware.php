<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role_id = null): Response
    {
        //
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $admin = Auth::guard('admin')->user();
        $adminRole = $admin->role_id;


        if ($role_id && $adminRole != $role_id) {
            return response()->json([
                'message' => 'Forbidden. Insufficient role permissions'
            ], 403);
        }

        return $next($request);
    }

}
