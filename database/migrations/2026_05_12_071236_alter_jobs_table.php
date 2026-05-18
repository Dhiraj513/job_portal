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
        // 1. Add a check to see if 'user_id' already exists
        if (!Schema::hasColumn('jobs', 'user_id')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->foreignId('user_id')->after('job_type_id')->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // 2. Add checks here too so rolling back doesn't crash if the column is missing
            if (Schema::hasColumn('jobs', 'user_id')) {
                // In Laravel, to drop a foreign key constraint by array, use dropForeign
                $table->dropForeign(['user_id']); 
                $table->dropColumn('user_id');
            }
        });
    }
};