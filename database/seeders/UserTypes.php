<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_types = [
            [
                'name' => 'Motora Super Admin',
            ],
            [
                'name' => 'Motora Admin',
            ],
            [
                'name' => 'Motora Marketing',
            ],
            [
                'name' => 'Motora HR',
            ],
            [
                'name' => 'Motora Sales',
            ],
            [
                'name' => 'Motora IT Support',
            ],
            [
                'name' => 'Organization Super Admin',
            ],
            [
                'name' => 'Organization Admin',
            ],
            [
                'name' => 'Organization Manager',
            ],
            [
                'name' => 'Organization Employee',
            ],
        ];

        foreach($user_types as $user_type) {
            UserType::create($user_type);
        }
    }
}
