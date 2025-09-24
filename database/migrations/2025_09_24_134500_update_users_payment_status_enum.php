<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum to include the statuses used by the app
        DB::statement("ALTER TABLE `users` MODIFY `payment_status` ENUM('pending','approved','rejected','paid','failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (pending, paid, failed)
        DB::statement("ALTER TABLE `users` MODIFY `payment_status` ENUM('pending','paid','failed') DEFAULT 'pending'");
    }
};
