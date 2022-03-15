<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('person');
            $table->string('address');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('phone');
            $table->string('source')->nullable();
            $table->string('followup_by');
            $table->date('followup_date');
            $table->date('last_followup_date')->nullable();
            $table->string('status')->default('first_call');
            $table->string('quality');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
