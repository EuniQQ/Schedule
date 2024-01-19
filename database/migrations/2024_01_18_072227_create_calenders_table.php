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
        Schema::create('calenders', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('birthday_person')->nullable();
            $table->boolean('mc_start')->default('false');
            $table->boolean('mc_end')->default('false');
            $table->string('plan')->nullable();
            $table->time('plan_time')->nullable();
            $table->string('tag_color')->nullable();
            $table->string('tag_title')->nullable();
            $table->string('tag_from')->nullable();
            $table->string('tag_to')->nullable();
            $table->string('sticker')->nullable();
            $table->string('photos_url')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calenders');
    }
};
