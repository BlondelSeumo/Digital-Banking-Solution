<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class AdminPermissionMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $admin = auth('admin')->user();
        if($admin->id != 1 && $admin->status == 0){
            auth('admin')->logout();
            $notify[]=['error','Your account has been blocked'];
            return back()->withNotify($notify);
        }

        if (!Role::hasPermission()) {
            return redirect()->route('admin.profile');
        }
        return $next($request);
    }
}

