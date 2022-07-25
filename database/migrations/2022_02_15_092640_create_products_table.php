<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('color_id')->index('color_id');
            $table->string('name')->nullable();
            $table->text('filename')->nullable();
            $table->decimal('price', 10, 0)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete 
            
            $table->foreign('category_id', 'products_ibfk_1')->references('id')->on('category_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
