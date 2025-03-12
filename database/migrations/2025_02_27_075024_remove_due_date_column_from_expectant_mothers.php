<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('expectant_mothers', function (Blueprint $table) {
            $table->dropColumn('due_date'); // Remove the due_date column
        });
    }

    public function down()
    {
        Schema::table('expectant_mothers', function (Blueprint $table) {
            $table->date('due_date')->nullable(); // Add due_date back if rolled back
        });
    }
};
