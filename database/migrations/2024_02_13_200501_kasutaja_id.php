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
        Schema::create('mang', function (Blueprint $table) {
            $table->string('game_id');
            $table->string('user_id');
            $table->string('comp_id')->nullable();
            $table->string('score_sum');
            $table->string('experience');
            $table->string('accuracy_sum');
            $table->string('game_count');
            $table->string('last_level');
            $table->string('time');
            $table->string('dt');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
