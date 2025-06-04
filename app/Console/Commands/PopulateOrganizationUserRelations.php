<?php

namespace App\Console\Commands;

use App\Models\LocationOrganization;
use App\Models\OrganizationUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateOrganizationUserRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-user-relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates the new organization_id and location_id for existing organization users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate user relations...');

        DB::transaction(function () {
            OrganizationUser::whereNull('organization_id')
                ->orWhereNull('location_id')
                ->chunkById(200, function ($users) {
                    foreach ($users as $user) {
                        // Find the pivot record using the old column
                        $locationOrg = LocationOrganization::find($user->loc_org_id);

                        if ($locationOrg) {
                            $user->organization_id = $locationOrg->org_id;
                            $user->location_id = $locationOrg->location_id;
                            $user->saveQuietly(); // Use saveQuietly to not trigger observers yet
                        }
                    }
                    $this->info('Processed 200 users...');
                });
        });

        $this->info('Population complete.');
        return Command::SUCCESS;
    }
}
