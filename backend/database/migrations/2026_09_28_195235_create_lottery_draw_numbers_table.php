<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lottery_draw_numbers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lottery_draw_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');

            $table->unsignedInteger('number');

            $table->unsignedTinyInteger('position');

            $table->timestampsTz();

            $table->unique([
                'lottery_draw_id',
                'type',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_draw_numbers');
    }
};