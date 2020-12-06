<?php


namespace App\Http\Controllers;


use App\Models\--pascal_singular--;

class --pascal_singular--Controller extends ApiResourceController
{
    protected $model = --pascal_singular--::class;

    protected function find($id)
    {
        --pascal_singular--::findOrFail($id);
    }
}
