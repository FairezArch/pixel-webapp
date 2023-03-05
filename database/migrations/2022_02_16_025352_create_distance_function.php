<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::unprepared('
        CREATE FUNCTION `GET_DISTANCE`(`lat1` DOUBLE, `lng1` DOUBLE, `lat2` DOUBLE, `lng2` DOUBLE, `unit` VARCHAR(2)) RETURNS double
            NO SQL
            DETERMINISTIC
        BEGIN
            DECLARE radlat1 DOUBLE;
            DECLARE radlat2 DOUBLE;
            DECLARE theta DOUBLE;
            DECLARE radtheta DOUBLE;
            DECLARE dist DOUBLE;
            SET radlat1 = PI() * lat1 / 180;
            SET radlat2 = PI() * lat2 / 180;
            SET theta = lng1 - lng2;
            SET radtheta = PI() * theta / 180;
            SET dist = sin(radlat1) * sin(radlat2) + cos(radlat1) * cos(radlat2) * cos(radtheta);
            SET dist = acos(dist);
            SET dist = dist * 180 / PI();
            SET dist = dist * 60 * 1.1515;
            -- SET dist = dist * 1.609344;
            if unit = "K" THEN SET dist = dist * 1.609344; END IF;
            if unit = "N" THEN SET dist = dist * 0.8684; END IF;
        RETURN dist;
        END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `GET_DISTANCE`');
    }
};
