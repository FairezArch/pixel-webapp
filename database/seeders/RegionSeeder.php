<?php

namespace Database\Seeders;

use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        Region::insert([
            ['name' => 'Solo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jogja', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
