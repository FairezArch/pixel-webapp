<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $currentChannel = Channel::firstWhere('name', 'Erafone 1');
        Store::insert([
            [
                'id' => 1, 'name' => 'Samsung SGM', 
                'channel_id' => $currentChannel->id,
                'promoter_ids' => json_encode([]), 'frontliner_ids' => json_encode([]),
                'location' => json_encode([-7.5664457, 110.8053703]),
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'id' => 2, 'name' => 'Samsung Paragon', 
                'channel_id' => $currentChannel->id,
                'promoter_ids' => json_encode([]), 'frontliner_ids' => json_encode([]),
                'location' => json_encode([-7.5624814, 110.81015]),
                'created_at' => $now, 'updated_at' => $now
            ],
        ]);
    }
}
