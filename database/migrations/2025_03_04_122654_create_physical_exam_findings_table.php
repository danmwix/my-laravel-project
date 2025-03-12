<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalExamFindingsTable extends Migration
{
    public function up()
    {
        Schema::create('physical_exam_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('expectant_mothers', 'mother_id')->onDelete('cascade');
            $table->text('abdominal_exam')->nullable();
            $table->text('urinalysis')->nullable();
            $table->text('blood_test')->nullable();
            $table->text('blood_pressure')->nullable();
            $table->text('ultrasound')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical_exam_findings');
    }
}