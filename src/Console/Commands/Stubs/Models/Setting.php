<?php namespace App\Models;

use App\Models\Contracts\ModelContract;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model implements ModelContract
{
    /**
     * @var string
     */
    protected $table = 'setting';

    /**
     * @var array
     */
    protected $fillable = [
        'group',
        'name',
        'value',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'group' => 'string',
        'name' => 'string',
        'value' => 'string',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
