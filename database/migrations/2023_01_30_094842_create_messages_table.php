<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender_id')->default('0');
            $table->bigInteger('receiver_id')->default('0');
            $table->bigInteger('conversation_id')->default('0');
            $table->text('message')->nullable();
            $table->text('file')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('file_url')->nullable();
            $table->text('file_type')->nullable();
            $table->text('file_size')->nullable();
            $table->integer('is_read')->default('0');
            $table->integer('delete_status')->default('0');
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
        Schema::dropIfExists('messages');
    }
}
