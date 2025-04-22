<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function hasPermission($code = null)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->id == 1) {
            return true;
        }

        $roleName        = $admin->role->name;
        $permissionCache = $roleName . '_permission';
        $permissions     = Cache::get($permissionCache);
        if (!$permissions) {
            $permissions = $admin->role->permissions->pluck('code')->toArray();
            Cache::put($permissionCache, $permissions);
        }

        if (is_array($code)) {

            $permissionsInString = implode(', ', $permissions);
            $codesInString       = implode(', ', $code);

            if (str_contains($codesInString, '*')) {
                foreach ($code as $route) {
                    $route = str_replace('*', '', $route);
                    if (str_contains($permissionsInString, $route)) {
                        return true;
                    }
                }
            }

            if (empty(array_intersect($code, $permissions))) {
                return false;
            }
            return true;
        }


        $allPermissions = Cache::get('AllPermissions');

        if (!$allPermissions) {
            $allPermissions = Permission::select('code')->get()->pluck('code')->toArray();
            Cache::put('AllPermissions', $allPermissions);
        }

        $routeName = $code ?? request()->route()->getName();
        if (in_array($routeName, $allPermissions) && !in_array($routeName, $permissions)) {
            return false;
        }

        return true;
    }

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole() || !app()->runningUnitTests()) {
            $roles = static::get()->map(function ($role) {
                return $role->name . '_permission';
            })->toArray();

            static::saved(function () use ($roles) {
                foreach ($roles as $value) {
                    \Cache::forget($value);
                }

                self::clearAllPermissionsCache();
            });

            static::deleted(function () {
                self::clearAllPermissionsCache();
            });
        }
    }

    protected static function clearAllPermissionsCache()
    {
        Cache::forget('AllPermissions');
    }
}
