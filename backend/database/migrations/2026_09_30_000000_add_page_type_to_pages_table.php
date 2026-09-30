<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('page_type', 20)->default(Page::TYPE_GUIDE)->index();
        });

        // Backfill: pages whose slug matches a lottery are lottery templates; "homepage" is the homepage.
        DB::table('pages')
            ->whereIn('slug', DB::table('lotteries')->select('slug'))
            ->update(['page_type' => Page::TYPE_LOTTERY]);

        DB::table('pages')->where('slug', 'homepage')->update(['page_type' => Page::TYPE_HOMEPAGE]);
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('page_type');
        });
    }
};
