<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckModule {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$names) {

        $permitted = false;
        $moduleName = null;

        foreach ($names as $name) {

            $permitted = @gs('modules')->$name ? true : false;

            if ($permitted) {
                break;
            }
            $moduleName = $name;
        }

        if ($permitted == true) {
            return $next($request);
        } else {

            if ($request->is('api/*')) {
                $notify[] = 'Sorry ' . keyToTitle($moduleName) . ' is not available now';
                return response()->json([
                    'remark'  => 'module_disable_error',
                    'status'  => 'error',
                    'message' => ['error' => $notify],
                ]);
            }

            abort(404);
        }
    }
}
