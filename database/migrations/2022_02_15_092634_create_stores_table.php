<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->longText('promoter_ids')->nullable();
            $table->longText('frontliner_ids')->nullable();
            $table->string('store_name')->nullable();
            $table->text('location')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes(); // Soft delete 
            
            $table->foreign('channel_id', 'stores_ibfk_2')->references('id')->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
