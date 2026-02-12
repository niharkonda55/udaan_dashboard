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
        Schema::table('projects', function (Blueprint $table) {
            // Add deadline fields
            $table->date('final_deadline')->nullable()->after('status');
            $table->date('cameraman_deadline')->nullable()->after('final_deadline');
            $table->date('editor_deadline')->nullable()->after('cameraman_deadline');
            
            // Add soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['final_deadline', 'cameraman_deadline', 'editor_deadline', 'deleted_at']);
        });
    }
};
