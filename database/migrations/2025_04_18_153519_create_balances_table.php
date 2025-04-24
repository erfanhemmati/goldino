<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coin_id')->constrained('coins')->onDelete('cascade');
            $table->decimal('total_amount', 18, 8)->default(0);
            $table->decimal('available_amount', 18, 8)->default(0);
            $table->decimal('locked_amount', 18, 8)->default(0);
            $table->unique(['user_id', 'coin_id']);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
