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
        Schema::table('group_members', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('name');
            $table->enum('gender', ['male', 'female'])->nullable()->after('nik');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('position')->nullable()->after('role'); // Posisi/jabatan di perusahaan
            $table->foreignId('parent_id')->nullable()->after('family_id')->constrained('group_members')->onDelete('cascade');
            
            // Add index for parent_id for better query performance
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn(['nik', 'gender', 'birth_date', 'position', 'parent_id']);
        });
    }
};
