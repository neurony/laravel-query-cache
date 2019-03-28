<?php

namespace Neurony\QueryCache\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Neurony\QueryCache\Traits\IsCacheable;

class Comment extends Model
{
    use IsCacheable;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'title',
        'content',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
