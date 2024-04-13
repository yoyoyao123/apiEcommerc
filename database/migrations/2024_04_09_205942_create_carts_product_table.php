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
        Schema::create('carts_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carts_id');
            $table->unsignedBigInteger('products_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->foreign('carts_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('carts_product');
    }
};