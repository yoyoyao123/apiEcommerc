<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('carts')) {
            Schema::create('commande
            ', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('users_id');
                $table->unsignedBigInteger('carts_id');
                $table->decimal('total', 8, 2);
                $table->string('statut')->default('en attente');
                $table->timestamps();

                $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('carts_id')->references('id')->on('carts')->onDelete('cascade');
            });
        }
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande');
    }
};
