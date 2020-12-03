<?php


namespace App\Traits;

use App\Helpers\UUIDHelper;
use Illuminate\Database\Eloquent\Model;

trait Uuid
{
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            /** @var Model $model */
            $key = $model->getKeyName();

            if (empty($model->$key)) {
                $model->$key = (string)UUIDHelper::generate();
            }
        });
    }
}
