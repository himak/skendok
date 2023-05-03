<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MultiTenantModelTrait
{
    private static int $accounting = 3;

    public static function bootMultiTenantModelTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {

            $isAdmin = auth()->user()->roles->contains(1);

            static::creating(function ($model) use ($isAdmin) {
                // Prevent admin from setting his own id - admin entries are global.
                // If required, remove the surrounding IF condition and admins will act as users
                if (!$isAdmin) {
                    $model->team_id = session()->get('team_id');
                }
            });

            if (!$isAdmin) {

                static::addGlobalScope('team_id', function (Builder $builder) {
                    $field = sprintf('%s.%s', $builder->getQuery()->from, 'team_id');
                    // $builder->where($field, auth()->user()->team_id)->orWhereNull($field);
                    $builder->whereIn($field, auth()->user()->teams->pluck('id'));

                    $routeArray = app('request')->route()->getAction();
                    $controllerAction = class_basename($routeArray['controller']);
                    list($controller, $action) = explode('@', $controllerAction);

                    if ($controller == 'PostController') {
                        if (count(array_intersect(auth()->user()->roles->pluck('id')->toArray(), [self::$accounting])) > 0) {
                            $builder->where('accounting', 1);
                        }
                    }

                });
            }
        }
    }
}
