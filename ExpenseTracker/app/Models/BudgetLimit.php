<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetLimit extends Model
{
    protected $fillable = ['user_id', 'category', 'amount'];

    public const DEFAULTS = [
        'food'           => 8000,
        'transportation' => 4000,
        'entertainment'  => 3000,
        'health'         => 5000,
        'shopping'       => 6000,
        'utilities'      => 3000,
        'other'          => 2000,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
