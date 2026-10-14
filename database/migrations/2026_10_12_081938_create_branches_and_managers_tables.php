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
        // 1. Branches Table
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 2. Branch Managers Table
        Schema::create('branch_managers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 3. Link Users to Hospital if column doesn't exist
        if (! Schema::hasColumn('users', 'hospital_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('hospital_id')->nullable()->after('hospital_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_managers');
        Schema::dropIfExists('branches');

        if (Schema::hasColumn('users', 'hospital_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('hospital_id');
            });
        }
    }
};
