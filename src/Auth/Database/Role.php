<?php

namespace OpenDeveloper\Developer\Auth\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenDeveloper\Developer\Traits\DefaultDatetimeFormat;

class Role extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('developer.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('developer.database.roles_table'));

        parent::__construct($attributes);
    }

    /**
     * A role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function administrators(): BelongsToMany
    {
        $pivotTable = config('developer.database.role_users_table');

        $relatedModel = config('developer.database.users_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'user_id');
    }

    /**
     * A role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        $pivotTable = config('developer.database.role_permissions_table');

        $relatedModel = config('developer.database.permissions_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'permission_id');
    }

    /**
     * A role belongs to many menus.
     *
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        $pivotTable = config('developer.database.role_menu_table');

        $relatedModel = config('developer.database.menu_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'menu_id');
    }

    /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission): bool
    {
        return !$this->can($permission);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->administrators()->detach();

            $model->permissions()->detach();
        });
    }
}
