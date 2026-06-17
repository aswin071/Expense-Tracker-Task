<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->float('amount');
            $table->text('description');
            $table->enum('category', [
                'food',
                'transportation',
                'entertainment',
                'health',
                'shopping',
                'utilities',
                'other',
            ]);
            $table->unsignedTinyInteger('day_of_month');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_logged_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_expenses');
    }
};
