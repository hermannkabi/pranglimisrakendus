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
        Schema::create('competitions', function (Blueprint $table){ 
            $table->bigIncrements('id', 20);
            $table->tinyText('comp_name');
            $table->tinyText('comp_description')->nullable();
            $table->timestamp('dt_start')->nullable();
            $table->timestamp('dt_end')->nullable();
            $table->tinyInteger('attempt_count')->nullable();
            $table->text('game_data');
            $table->text('participants');
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
