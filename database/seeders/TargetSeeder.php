<?php

namespace Database\Seeders;

use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Target::insert([
            [
                'user_id' => 1,
                'nominal' => 30000,
                'date' => Carbon::parse('2022-02-01'),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 1,
                'nominal' => 50000,
                'date' => Carbon::parse('2022-02-14'),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
        ]);
    }
}
