<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotBranchStaff {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = "branch_staff") {
        if (!Auth::guard($guard)->check()) {
            return to_route('staff.login');
        }

        if (Auth::guard($guard)->user()->status == Status::STAFF_BAN) {
            return to_route('staff.banned');
        }

        if (!session('branchId')) {
            session()->put('branchId', authStaff()->branch()->id);
        }

        return $next($request);
    }
}
