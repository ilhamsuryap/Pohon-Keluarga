<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add `type` column to families
        Schema::table('families', function (Blueprint $table) {
            $table->enum('type', ['family', 'company'])->default('family')->after('family_name');
        });

        // Create group_members table for company/group members
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained('families')->onDelete('cascade');
            $table->string('name');
            $table->string('role')->nullable(); // e.g. director, manager, staff, intern
            $table->string('relation_type')->nullable(); // optional extra info
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_members');

        Schema::table('families', function (Blueprint $table) {
            if (Schema::hasColumn('families', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
