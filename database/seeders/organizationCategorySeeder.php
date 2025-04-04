<?php

namespace Database\Seeders;

use App\Models\OrganizationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class organizationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization_categories = [
            ['name' => 'Department of Motor Traffic'],
            ['name' => 'Divisional Secretariat'],
            ['name' => 'Emission Test Center'],
            ['name' => 'Insurance Company'],
            ['name' => 'Service Center'],
        ];
        foreach ($organization_categories as $organization_category) {
            OrganizationCategory::create($organization_category);
        }
    }
}
