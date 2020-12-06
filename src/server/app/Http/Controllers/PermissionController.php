<?php


namespace App\Http\Controllers;


use App\Models\Permission;

class PermissionController extends ApiResourceController
{
    protected $model = Permission::class;

    protected function find($id)
    {
        Permission::findOrFail($id);
    }
}
