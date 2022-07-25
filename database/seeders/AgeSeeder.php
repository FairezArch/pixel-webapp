<?php

namespace Database\Seeders;

use App\Models\Age;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(['age'=>'1-20'],['age'=>'21-30'],['age'=>'31-40'],['age'=>'41-50'],['age'=>'50 keatas']);
        
        Age::insert($data);
    }
}
