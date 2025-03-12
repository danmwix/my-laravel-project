<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('expectant_mothers', 'mother_id')->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('nurses', 'nurse_id')->onDelete('cascade'); // Updated to reference nurse_id
            $table->date('date');
            $table->time('time');
            $table->string('reason');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
