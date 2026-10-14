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
        // 1. Test Categories (e.g. Hematology, Biochemistry, Pathology)
        Schema::create('test_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index(); // null means All Branches
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 2. Lab Tests & Pricing / Fees (e.g. CBC, LFT, Lipid Profile)
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index(); // null means All Branches
            $table->unsignedBigInteger('test_category_id')->nullable()->index();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('sample_type')->nullable(); // e.g. Blood, Urine, Swab
            $table->string('turnaround_time')->nullable(); // e.g. 6 Hours, Same Day
            $table->string('method')->nullable(); // e.g. Automated Analyzer
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('test_categories');
    }
};
