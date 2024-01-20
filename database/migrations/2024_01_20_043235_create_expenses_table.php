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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('使用者ID');
            $table->date('date')->comment('日期');
            $table->string('item')->comment('項目');
            $table->string('shop')->nullable()->comment('消費店家');
            $table->integer('amount')->comment('金額');
            $table->integer('actual_pay')->nullable()->comment('實際花費');
            $table->tinyInteger('card')->nullable()->comment('1=玉山,2=國泰');
            $table->string('note')->nullable()->comment('備註');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_expenses');
    }
};
