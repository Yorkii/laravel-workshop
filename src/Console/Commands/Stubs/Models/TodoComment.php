<?php namespace App\Models;

use Yorki\Repositories\Contracts\ModelContract;
use Illuminate\Database\Eloquent\Model;

class TodoComment extends Model implements ModelContract
{
    /**
     * @var string
     */
    protected $table = 'todo_comment';

    /**
     * @var array
     */
    protected $fillable = [
        'todo_id',
        'user_id',
        'comment',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'todo_id' => 'integer',
        'user_id' => 'integer',
        'comment' => 'string',
    ];
}
