<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends CsvSeeder
{
    function __construct() {
        $this->table = "products";
        $this->filename = base_path().'/database/seeders/seeds/products.csv';
        $this->timestamps = true;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        parent::run();
    }
}
