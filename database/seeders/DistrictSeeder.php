<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            // Central Province
            [
                'province_id' => 1,
                'name' => 'Kandy'
            ],
            [
                'province_id' => 1,
                'name' => 'Matale'
            ],
            [
                'province_id' => 1,
                'name' => 'Nuwara Eliya'
            ],
            // Eastern Province
            [
                'province_id' => 2,
                'name' => 'Ampara'
            ],
            [
                'province_id' => 2,
                'name' => 'Batticaloa'
            ],
            [
                'province_id' => 2,
                'name' => 'Trincomalee'
            ],
            // North Central Province
            [
                'province_id' => 3,
                'name' => 'Anuradhapura'
            ],
            [
                'province_id' => 3,
                'name' => 'Polonnaruwa'
            ],
            // Northern Province
            [
                'province_id' => 4,
                'name' => 'Jaffna'
            ],
            [
                'province_id' => 4,
                'name' => 'Kilinochchi'
            ],
            [
                'province_id' => 4,
                'name' => 'Mannar'
            ],
            [
                'province_id' => 4,
                'name' => 'Mullaitivu'
            ],
            [
                'province_id' => 4,
                'name' => 'Vavuniya'
            ],
            // North Western Province
            [
                'province_id' => 5,
                'name' => 'Kurunegala'
            ],
            [
                'province_id' => 5,
                'name' => 'Puttalam'
            ],
            // Sabaragamuwa Province
            [
                'province_id' => 6,
                'name' => 'Kegalle'
            ],
            [
                'province_id' => 6,
                'name' => 'Ratnapura'
            ],
            // Southern Province
            [
                'province_id' => 7,
                'name' => 'Galle'
            ],
            [
                'province_id' => 7,
                'name' => 'Hambantota'
            ],
            [
                'province_id' => 7,
                'name' => 'Matara'
            ],
            // Uva Province
            [
                'province_id' => 8,
                'name' => 'Badulla'
            ],
            [
                'province_id' => 8,
                'name' => 'Monaragala'
            ],
            // Western Province
            [
                'province_id' => 9,
                'name' => 'Colombo'
            ],
            [
                'province_id' => 9,
                'name' => 'Gampaha'
            ],
            [
                'province_id' => 9,
                'name' => 'Kalutara'
            ]

        ];
        foreach ($districts as $district) {
            District::create($district);
        }
    }
}
















