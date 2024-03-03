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
        Schema::create('klass', function (Blueprint $table) {
            $table->string('klass_id');
            $table->string('klass_name');
            $table->string('klass_password');
            $table->string('student_list');
            $table->string('teacher');
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
