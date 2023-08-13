<?php

namespace OpenDeveloper\Developer\Auth\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenDeveloper\Developer\Traits\DefaultDatetimeFormat;

class OperationLog extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input'];

    public static $methodColors = [
        'GET'    => 'success',
        'POST'   => 'primary',
        'PUT'    => 'info',
        'DELETE' => 'danger',
    ];

    public static $methods = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH',
        'LINK', 'UNLINK', 'COPY', 'HEAD', 'PURGE',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('developer.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('developer.database.operation_log_table'));

        parent::__construct($attributes);
    }

    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('developer.database.users_model'));
    }
}
