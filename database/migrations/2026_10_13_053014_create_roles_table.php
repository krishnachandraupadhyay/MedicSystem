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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        if (! Schema::hasColumn('branch_managers', 'role_id')) {
            Schema::table('branch_managers', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('branch_id');
                $table->string('role_name')->nullable()->after('role_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('branch_managers', 'role_id')) {
            Schema::table('branch_managers', function (Blueprint $table) {
                $table->dropColumn(['role_id', 'role_name']);
            });
        }

        Schema::dropIfExists('roles');
    }
};
