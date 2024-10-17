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
        Schema::create('klasses', function (Blueprint $table) {
            $table->string('klass_id')->primary();
            $table->string('klass_name');
            $table->string('klass_password');
            $table->integer('teacher_id')->length(11);
            $table->timestamps();
            $table->string('uuid');

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
