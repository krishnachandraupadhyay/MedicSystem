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
            $table->boolean('enable_laboratory')->default(false)->after('status');
            $table->boolean('enable_multi_branch')->default(false)->after('enable_laboratory');
            $table->boolean('enable_pharmacy')->default(false)->after('enable_multi_branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn(['enable_laboratory', 'enable_multi_branch', 'enable_pharmacy']);
        });
    }
};
