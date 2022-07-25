<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AgeSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CategoryProductSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(ChannelSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(UserSeeder::class);
    }
}
