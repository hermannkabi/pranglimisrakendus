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
         Schema::table('competitions', function (Blueprint $table) {
            // Drop old 'id' column if exists
            if (Schema::hasColumn('competitions', 'id')) {
                $table->dropColumn('id');
            }

            // Add new competition_id as primary key (big unsigned integer, adjust if you want string)
            $table->unsignedBigInteger('competition_id')->primary()->first();

            // Note: 'first()' attempts to place column at start, adjust if needed
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
