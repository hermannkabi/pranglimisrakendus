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
       
        Schema::create('users', function (Blueprint $table){ 
            $table->bigIncrements('id', 20);
            $table->index('email');
            $table->string('google_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->int('klass', 255)->nullable();
            $table->string('eesnimi');
            $table->string('perenimi');
            $table->string('role')->default("student");
            $table->text('settings')->nullable();
            $table->int('streak', 11)->nullable();
            $table->text('profile_pic')->default("'/assets/logo.png'"); 
            $table->tinyInteger('streak_active', 1)->default('0'); 
        
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
