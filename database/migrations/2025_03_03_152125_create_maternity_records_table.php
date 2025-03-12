<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaternityRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('maternity_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('expectant_mothers', 'mother_id')->onDelete('cascade');
            $table->float('weight')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->float('temperature')->nullable();
            $table->float('height')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maternity_records');
    }
}
