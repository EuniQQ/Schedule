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
        Schema::table('styles', function (Blueprint $table) {
            $table->string('main_img')->default('/public/storage/img/')->change();
            $table->string('footer_color')->default('rgba(190, 61, 61, 1)')->change();
            $table->string('bg_color')->default('rgba(240, 237, 237, 1)')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('styles', function (Blueprint $table) {
            //
        });
    }
};
