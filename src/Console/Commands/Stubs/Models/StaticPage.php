<?php namespace App\Models;

use App\Models\Contracts\ModelContract;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model implements ModelContract
{
    /**
     * @var string
     */
    protected $table = 'static_page';

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
        'title' => 'string',
        'content' => 'string',
    ];
}
