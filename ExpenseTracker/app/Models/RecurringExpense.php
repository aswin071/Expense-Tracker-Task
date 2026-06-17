<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringExpense extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'category',
        'day_of_month',
        'is_active',
        'last_logged_at',
    ];

    protected $casts = [
        'amount'         => 'float',
        'is_active'      => 'boolean',
        'last_logged_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
