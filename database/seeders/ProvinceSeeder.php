<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'Central Province'],
            ['name' => 'Eastern Province'],
            ['name' => 'North Central Province'],
            ['name' => 'Northern Province'],
            ['name' => 'North Western Province'],
            ['name' => 'Sabaragamuwa Province'],
            ['name' => 'Southern Province'],
            ['name' => 'Uva Province'],
            ['name' => 'Western Province'],
        ];
        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
}








