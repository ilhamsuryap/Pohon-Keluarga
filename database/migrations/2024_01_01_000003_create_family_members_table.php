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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->date('death_date')->nullable();
            $table->enum('relation', ['father', 'mother', 'child']);
            $table->foreignId('parent_id')->nullable()->constrained('family_members')->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('has_children')->default(false);
            $table->timestamps();
            
            $table->index(['name', 'birth_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};