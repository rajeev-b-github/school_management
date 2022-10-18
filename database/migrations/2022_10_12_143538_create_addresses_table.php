<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            //$table->bigIncrements('user_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('address_1');
            $table->string('address_2');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('pin_code');
            $table->timestamps();
            // $table->foreignId('user_id')
            //     ->constrained('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
