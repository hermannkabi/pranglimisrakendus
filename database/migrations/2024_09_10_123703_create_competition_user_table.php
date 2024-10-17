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
        Schema::create('competition_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('comp_id')->nullable();
        });

        Schema::table('competition_user', function (Blueprint $table) {
            //TODO: kas tasub kustutada, kui kasutaja kustub / kui mang kustub? (Kindlasti tasub, muidu tekivad vead edetabelis jne pärast)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('comp_id')->references('id')->on('competitions')->onDelete('cascade');
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
