<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mother_id');
            $table->foreign('mother_id')->references('mother_id')->on('expectant_mothers')->onDelete('cascade');
            $table->foreignId('pharmacist_id')->constrained('pharmacists')->onDelete('cascade');
            $table->string('medication_name');
            $table->text('dosage_instructions');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
}