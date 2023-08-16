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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('menu_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('restaurant_owner_id')->unsigned();
            $table->bigInteger('quantity')->unsigned()->default(0);
            $table->bigInteger('total')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('restrict');
            $table->foreign('restaurant_owner_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('restrict');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
