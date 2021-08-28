<?php

namespace App\Models;

use \Backpack\PermissionManager\app\Models\Permission as BasePermission;

class Permission extends BasePermission
{

    /**
     * @var array
     */
    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at', 'label'];

}
