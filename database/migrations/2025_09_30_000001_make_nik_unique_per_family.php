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
        Schema::table('family_members', function (Blueprint $table) {
            // Attempt to drop previous global unique index on nik (if present)
            try {
                $table->dropUnique('family_members_nik_unique');
            } catch (\Exception $e) {
                // ignore when index does not exist
            }

            // Only create composite unique index (family_id + nik) to allow same nik across different families
            try {
                $table->unique(['family_id', 'nik'], 'family_members_family_nik_unique');
            } catch (\Exception $e) {
                // ignore if already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            try {
                $table->dropUnique('family_members_family_nik_unique');
            } catch (\Exception $e) {
            }

            // restore global unique on nik (previous behavior)
            try {
                $table->unique('nik', 'family_members_nik_unique');
            } catch (\Exception $e) {
            }
        });
    }
};
