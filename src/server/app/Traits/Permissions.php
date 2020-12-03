<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUndefinedMethodInspection */


namespace App\Traits;


use App\Models\Permission;
use exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait Permissions
{

    protected static function bootPermissions()
    {
        static::creating(function ($model) {
            try {
                Schema::create('user_permissions', function (Blueprint $table) {
                    $table->id();
                    $table->string('user_id');
                    $table->string('permission_id');
                    $table->timestamps();
                });
            } catch (exception $e) {
            }
        });
    }

    public function allow($permission)
    {
        if (!$this->allowedTo($permission)) {
            $perm = Permission::query()->where('name', 'LIKE', $permission);
            if ($perm->count() > 0)
                $perm = $perm->first()->getModel();
            else {
                $perm = new Permission(['name' => $permission]);
                $perm->save();
            }
            $this->permissions()->attach($perm);
            $this->save();
            $this->refresh();
        }
    }

    public function allowedTo($needle)
    {
        foreach ($this->permissions as $perm)
            if ($perm->name == $needle) return true;
        return false;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * @param string|Permission $permission
     * @return bool
     */
    public function withdraw($permission)
    {
        $perm =
            $permission instanceof Permission
                ? $permission
                : $this->permissions->where('name', $permission)->first()->getModel();
        if ($perm) {
            var_dump('tt');
            $this->permissions()->detach($perm);
            $this->save();
            $this->refresh();
            return true;
        } else return false;
    }
}
