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
            $table->foreignId('user_id')->comment('使用者ID');
            $table->date('date')->comment('日期');
            $table->string('birthday_person')->nullable()->comment('當日壽星');
            $table->tinyInteger('is_mc_start')->default(0)->comment('0=否,1=是');
            $table->tinyInteger('is_mc_end')->default(0)->comment('0=否,1=是');
            $table->string('plan')->nullable()->comment('計畫');
            $table->time('plan_time')->nullable()->comment('計畫時間');
            $table->string('tag_color')->nullable()->comment('標籤色');
            $table->string('tag_title')->nullable()->comment('標籤名');
            $table->date('tag_from')->nullable()->comment('標籤開始');
            $table->date('tag_to')->nullable()->comment('標籤結束');
            $table->string('sticker')->nullable()->comment('標籤照片');
            $table->string('photos_link')->nullable()->comment('照片集連結');
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
