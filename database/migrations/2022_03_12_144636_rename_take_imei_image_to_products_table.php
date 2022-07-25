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
        Schema::table('products', function (Blueprint $table) {
            //
            $table->renameColumn('take_imei_image','takeImei');    
        });

        Schema::table('sales', function (Blueprint $table) {
            //
            $table->integer('quantity')->after('customer_id')->default(0)->change();
            $table->bigInteger('nominal')->after('quantity')->default(0)->change();
            $table->string('imei_filename')->after('nominal')->nullable()->change();
            $table->string('photo_filename')->after('imei_filename')->nullable()->change();    
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->renameColumn('takeImei','take_imei_image');            //
        });

        Schema::table('sales', function (Blueprint $table) {
            //
            $table->dropColumn('quantity');
            $table->dropColumn('nominal');
            $table->dropColumn('imei_filename');
            $table->dropColumn('photo_filename');    
        });
    }
};
