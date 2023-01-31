<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExceptionManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exception_management', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->text('stack_trace')->nullable();
            $table->text('file')->nullable();
            $table->text('line')->nullable();
            $table->text('header_info')->nullable();
            $table->text('ip')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('exception_management');
    }
}
