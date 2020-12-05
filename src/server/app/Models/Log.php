<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'event'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];
}
