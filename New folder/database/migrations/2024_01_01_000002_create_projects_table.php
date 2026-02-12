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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', [
                'created',
                'assigned',
                'shooting',
                'raw_uploaded',
                'editing',
                'review',
                'approved',
                'rework',
                'completed'
            ])->default('created');
            $table->foreignId('cameraman_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('editor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('raw_media_method', ['physical', 'online'])->nullable();
            $table->string('raw_media_link')->nullable();
            $table->text('cameraman_notes')->nullable();
            $table->text('editor_notes')->nullable();
            $table->enum('final_delivery_method', ['physical', 'online'])->nullable();
            $table->string('final_delivery_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

