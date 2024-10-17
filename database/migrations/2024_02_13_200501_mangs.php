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
        Schema::create('mangs', function (Blueprint $table) {
            $table->id('pk');
            $table->string('game_id');
            $table->string('user_id');
            $table->string('comp_id')->nullable();
            $table->string('score_sum');
            $table->string('experience');
            $table->string('accuracy_sum');
            $table->string('game_count');
            $table->string('last_level');
            $table->string('time');
            $table->timestamps();
            $table->text('log');  
            $table->string('last_equation');
            $table->text('game')->nullable()->default(NULL);
            $table->text('game_type')->nullable()->default(NULL);  
            $table->bigInteger("competition_id")->nullable();
            
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
