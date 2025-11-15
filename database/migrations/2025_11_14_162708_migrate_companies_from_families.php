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
        // Migrate companies from families table to companies table
        $companies = DB::table('families')
            ->where('type', 'company')
            ->get();

        foreach ($companies as $company) {
            DB::table('companies')->insert([
                'id' => $company->id,
                'user_id' => $company->user_id,
                'company_name' => $company->family_name,
                'description' => $company->description,
                'privacy' => $company->privacy,
                'created_at' => $company->created_at,
                'updated_at' => $company->updated_at,
            ]);
        }

        // Update group_members to reference companies
        DB::table('group_members')
            ->whereIn('family_id', $companies->pluck('id'))
            ->update(['family_id' => DB::raw('family_id')]); // Keep same ID for now, will be updated in next migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move companies back to families table
        $companies = DB::table('companies')->get();

        foreach ($companies as $company) {
            DB::table('families')
                ->where('id', $company->id)
                ->update([
                    'type' => 'company',
                    'family_name' => $company->company_name,
                    'description' => $company->description,
                    'privacy' => $company->privacy,
                ]);
        }
    }
};
