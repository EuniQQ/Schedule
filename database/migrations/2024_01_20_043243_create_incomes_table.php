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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('使用者ID');
            $table->date('date')->comment('日期');
            $table->string('content')->comment('內容');
            $table->string('payer')->comment('支薪者');
            $table->integer('amount')->comment('金額');
            $table->string('bank')->comment('入帳銀行');
            $table->integer('tithe')->nullable()->comment('十一金額');
            $table->date('tithe_date')->nullable()->comment('十一日期');
            $table->string('tithe_obj')->nullable()->comment('十一對象');
            $table->string('note')->nullable()->comment('備註');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_incomes');
    }
};
