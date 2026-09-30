<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('placement')->nullable();
            $table->string('target_url', 2048);
            $table->string('ip', 45)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer', 2048)->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index('slug');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_clicks');
    }
};
