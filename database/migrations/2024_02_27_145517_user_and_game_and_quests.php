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
        Schema::table('users', function (Blueprint $table) {

            $table->string('score_sum');
            $table->string('experience');
            $table->string('accuracy_sum');
            $table->string('game_count');
            $table->string('last_level');
            $table->string('last_equation');
            $table->string('time');
            $table->string('dt');

            $table->string('mistakes_tendency');
            $table->string('mistakes_sum');

            $table->string('quests');
            $table->string('quest_type');
            $table->string('completed_quests_sum');
            
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
