<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('emergency_messages', function (Blueprint $table) {
            $table->id(); // Default primary key for this table
            $table->bigInteger('mother_id')->unsigned(); // Foreign key matching BIGINT UNSIGNED
            $table->string('name'); // Added field for patient's name
            $table->string('phone'); // Added field for patient's phone number
            $table->text('message'); // Changed to text for potentially longer messages
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('mother_id')
                  ->references('mother_id')
                  ->on('expectant_mothers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emergency_messages');
    }
}