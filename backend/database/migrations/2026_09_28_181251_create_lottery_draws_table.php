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
        Schema::create('lottery_draws', function (Blueprint $table) {
        $table->id();

        $table->foreignId('lottery_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->date('draw_date');

        $table->json('numbers');
        $table->json('bonus_numbers')->nullable();

        $table->decimal('jackpot', 15, 2)->nullable();
        $table->string('jackpot_currency', 3)->nullable();

        $table->string('source_url')->nullable();

        $table->timestampsTz();

        $table->unique(['lottery_id', 'draw_date']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_draws');
    }
};
