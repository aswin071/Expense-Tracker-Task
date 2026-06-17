<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    const CATEGORIES = [
        'food',
        'transportation',
        'entertainment',
        'health',
        'shopping',
        'utilities',
        'other',
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'category',
        'date',
        'receipt_image',
    ];

    protected $casts = [
        'date'   => 'datetime',
        'amount' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
