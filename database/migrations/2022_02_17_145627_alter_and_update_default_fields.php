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
        Schema::table('channels', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->integer('status')->default(1)->after('color');
        });
        Schema::table('targets', function (Blueprint $table) {
            $table->integer('status')->default(1)->after('date');
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
            $table->dropColumn(['status']);
        });
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
};
