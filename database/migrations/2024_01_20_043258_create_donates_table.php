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
        Schema::create('donates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('使用者ID');
            $table->integer('method')->comment('方式');
            $table->string('name')->comment('機構名稱');
            $table->string('account')->nullable()->comment('帳戶');
            $table->string('pay_on_line')->nullable()->comment('線上付款');
            $table->string('bank')->nullable()->comment('銀行');
            $table->string('form_link')->nullable()->comment('填寫表單連結');
            $table->string('tel')->nullable()->comment('連絡電話');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_donates');
    }
};
