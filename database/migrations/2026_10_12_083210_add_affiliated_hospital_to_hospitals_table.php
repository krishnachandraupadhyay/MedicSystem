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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->foreignId('affiliated_hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->string('affiliated_hospital_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropForeign(['affiliated_hospital_id']);
            $table->dropColumn(['affiliated_hospital_id', 'affiliated_hospital_name']);
        });
    }
};
