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
            Schema::create('commands', function (Blueprint $table) {
                $table->id();
                $table->foreignId('users_id')->constrained();
                $table->foreignId('carts_id')->constrained();
                $table->decimal('total', 8, 2);
                $table->string('statut')->default('en attente');
                $table->timestamps();
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
        Schema::dropIfExists('commands');
    }
};
