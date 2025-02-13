<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailConfirmation extends Model
{
    protected $table = 'confirmation_tokens';

    protected $fillable = [
        'user_id',
        'confirmation_token'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
