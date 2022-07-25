<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RolePermission extends Model
{
    use HasFactory;

    public function scopeStoreData($query, $request)
    {
        # code...
        $add = Role::create(['name' => strtolower($request->name)]);

        return $add->syncPermissions($request->input('permission'));
    }

    public function scopeUpdateData($query, $role_permission, $request)
    {
        # code...
        $role_permission->update(['name' => strtolower($request->input('name'))]);
        
        return $role_permission->syncPermissions($request->input('permission'));

    }


}
