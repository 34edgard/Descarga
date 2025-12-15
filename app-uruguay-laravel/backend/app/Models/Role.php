<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SpatieRole
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        //'guard_name',
        'description',
        'is_protected',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
    ];


    public static function boot()
    {
        parent::boot();

        static::updating(function ($role) {
            if ($role->is_protected && $role->isDirty('name')) {
                throw new \Exception('No se puede modificar un rol protegido');
            }
        });

        static::deleting(function ($role) {
            if ($role->is_protected) {
                throw new \Exception('No se puede eliminar un rol protegido');
            }
        });
    }
}
