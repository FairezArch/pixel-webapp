<?php

namespace Database\Seeders;

use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        Channel::insert([
            ['name' => 'Erafone 1', 'region_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Erafone 2', 'region_id' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Erafone 3', 'region_id' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Erafone 4', 'region_id' => '2', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
