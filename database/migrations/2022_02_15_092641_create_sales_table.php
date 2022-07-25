<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('quantity');
            $table->bigInteger('nominal');
            $table->string('imei_filename');
            $table->string('photo_filename');
            $table->timestamps();
            $table->softDeletes(); // Soft delete 
            
            $table->foreign('product_id', 'sales_ibfk_1')->references('id')->on('products');
            $table->foreign('customer_id', 'sales_ibfk_2')->references('id')->on('customers');
            $table->foreign('store_id', 'sales_ibfk_3')->references('id')->on('stores');
            $table->foreign('user_id', 'sales_ibfk_4')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
