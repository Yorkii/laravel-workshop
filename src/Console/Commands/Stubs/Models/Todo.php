<?php namespace App\Models;

use Yorki\Repositories\Contracts\ModelContract;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model implements ModelContract
{
    const STATUS_NEW = 1;
    const STATUS_DONE = 2;
    const STATUS_SKIPPED = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_ASAP = 4;

    const CATEGORY_CODE = 1;
    const CATEGORY_DESIGN = 2;
    const CATEGORY_OTHER = 3;

    /**
     * @var string
     */
    protected $table = 'todo';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'text',
        'done_at',
        'done_by',
        'priority',
        'status',
        'category',
        'title',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'text' => 'string',
        'done_by' => 'integer',
        'priority' => 'integer',
        'status' => 'integer',
        'category' => 'integer',
        'title' => 'string',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'done_at',
    ];
}
