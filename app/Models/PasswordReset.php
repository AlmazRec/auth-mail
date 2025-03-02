<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    protected $fillable = [
        'user_id',
        'reset_token'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
