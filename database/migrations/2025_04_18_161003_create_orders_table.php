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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('base_coin_id')->constrained('coins')->onDelete('cascade');
            $table->foreignId('quote_coin_id')->constrained('coins')->onDelete('cascade');
            $table->enum('type', ['BUY', 'SELL']);
            $table->decimal('amount', 24, 8)->default(0)->unsigned();
            $table->decimal('remaining_amount', 24, 8)->default(0)->unsigned();
            $table->decimal('filled_amount', 24, 8)->default(0)->unsigned();
            $table->decimal('price', 24, 8)->default(0)->unsigned();
            $table->decimal('total', 24, 8)->default(0)->unsigned();
            $table->enum('status', ['OPEN', 'COMPLETED', 'CANCELED'])->default('OPEN');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
