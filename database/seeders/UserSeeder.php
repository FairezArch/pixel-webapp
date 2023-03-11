<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $admin = User::create([
            'store_id' => 0,
            'name' => 'Udin Worldwide admin',
            'password' => Hash::make('ByuRtuqVe'),
            'mobile' => '+628111111111111',
            'email' => 'testing@example.net',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $admin->assignRole('super-admin');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $currentStore = Store::firstWhere('name', 'Samsung SGM');
        $user = User::create([
            'store_id' => $currentStore->id,
            'name' => 'Udin Worldwide',
            'password' => Hash::make('qwerty1234'),
            'mobile' => '+628111111111111',
            'email' => 'udin@gmail.com',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $user->assignRole('promotor');

        $user1 = User::create([
            'store_id' => $currentStore->id,
            'name' => 'Lavolpe',
            'password' => Hash::make('qwerty1234'),
            'mobile' => '+628111111111112',
            'email' => 'lavolpe@hotmail.com',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $user1->assignRole('frontliner');
    }
}
