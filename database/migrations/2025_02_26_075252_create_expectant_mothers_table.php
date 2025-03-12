<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpectantMothersTable extends Migration
{
    public function up()
    {
        Schema::create('expectant_mothers', function (Blueprint $table) {
            $table->id('mother_id'); // Auto-incrementing primary key
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('place_of_residence')->nullable();
            $table->date('due_date')->nullable();
            $table->string('pregnancy_status')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('expectant_mothers');
    }
}