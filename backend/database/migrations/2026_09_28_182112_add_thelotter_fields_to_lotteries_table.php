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
    $table->string('thelotter_name')->nullable();
    $table->string('thelotter_url')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                Schema::table('lotteries', function (Blueprint $table) {
                    $table->dropColumn('thelotter_name');
                    $table->dropColumn('thelotter_url');
                });
    }
};
