<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class organizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            // Government Agencies
            [   'org_cat_id' => 1,
                'name' => 'Department of Motor Traffic'
            ],
            [   'org_cat_id' => 1,
                'name' => 'Divisional Secretariat'
            ],
            // Emission Test Centers
            [   'org_cat_id' => 2,
                'name' => 'Laugf eco Sri (pvt) Ltd'
            ],
            [   'org_cat_id' => 2,
                'name' => 'Drive Green (pvt) Ltd'
            ],
            // Insurance Companies
            [   'org_cat_id' => 3,
                'name' => 'Allianz Insurance Lanka Ltd'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Amana Takaful PLC'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Ceylinco General Insurance Limited'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Continental Insurance Lanka Ltd'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Cooperative Insurance Company PLC'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Fairfirst Insurance Limited'
            ],
            [   'org_cat_id' => 3,
                'name' => 'HNB General Insurance Ltd'
            ],
            [   'org_cat_id' => 3,
                'name' => 'LOLC General Insurance PLC'
            ],
            [   'org_cat_id' => 3,
                'name' => 'MBSL Insurance Company Limited'
            ],
            [   'org_cat_id' => 3,
                'name' => 'National Insurance Trust Fund'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Orient Insurance Limited'
            ],
            [   'org_cat_id' => 3,
                'name' => 'People’s Insurance PLC'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Sanasa General Insurance Company Limited'
            ],
            [   'org_cat_id' => 3,
                'name' => 'Sri Lanka Insurance Corporation General Limited'
            ],
            // Service Centers
            [   'org_cat_id' => 4,
                'name' => 'Abans Auto (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Access Motors (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Auto Bavaria (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Auto Miraj'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Bodyshop by Access Motors'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Browns Hybrid Care'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Driveline'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Ideal First Choice (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Ideal Motors (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Indra Service Park'
            ],
            [   'org_cat_id' => 4,
                'name' => 'LAUGFS Car Care'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Mag City Sri Lanka'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Prestige Automobile (Pvt) Ltd'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Sterling Aftercare Centre'
            ],
            [   'org_cat_id' => 4,
                'name' => 'Toyota Lanka'
            ],
            [   'org_cat_id' => 4,
                'name' => 'United Motors'
            ],
        ];

        foreach ($organizations as $organization) {
            Organization::create($organization);
        }
    }
}









