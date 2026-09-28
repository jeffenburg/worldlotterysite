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
        Schema::table('lotteries', function (Blueprint $table) {
    $table->unsignedTinyInteger('main_numbers_count')->nullable();
    $table->unsignedTinyInteger('bonus_numbers_count')->default(0);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotteries', function (Blueprint $table) {
            $table->dropColumn([
                'main_numbers_count',
                'bonus_numbers_count',
            ]);
        });
    }
};
