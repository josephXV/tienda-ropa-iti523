<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    protected $table = 'comentarios';

    protected $fillable = [
        'commentable_type',
        'idea_id',
        'user_id',
        'content',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function idea(): BelongsTo
{
    return $this->belongsTo(Idea::class);
}
}