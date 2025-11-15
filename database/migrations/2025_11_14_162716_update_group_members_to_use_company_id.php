<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
        });

        // Add new company_id column
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id');
        });

        // Copy data from family_id to company_id
        DB::table('group_members')->update(['company_id' => DB::raw('family_id')]);

        // Make company_id not nullable
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable(false)->change();
        });

        // Add foreign key constraint for company_id
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Drop old family_id column
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('family_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        // Add back family_id column
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreignId('family_id')->nullable()->after('id');
        });

        // Copy data from company_id to family_id
        DB::table('group_members')->update(['family_id' => DB::raw('company_id')]);

        // Make family_id not nullable
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreignId('family_id')->nullable(false)->change();
        });

        // Add foreign key constraint for family_id
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreign('family_id')->references('id')->on('families')->onDelete('cascade');
        });

        // Drop company_id column
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
};
