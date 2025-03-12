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
        Schema::table('expectant_mothers', function (Blueprint $table) {
            if (!Schema::hasColumn('expectant_mothers', 'phone')) {
                $table->string('phone')->nullable()->after('pregnancy_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expectant_mothers', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};