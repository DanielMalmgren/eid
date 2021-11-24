<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\FrejaAPI;
use LdapRecord\Models\ActiveDirectory\User;
use LdapRecord\Models\Attributes\AccountControl;

class FrejaPurgeOldOrgIds extends Command
{
    use FrejaAPI;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freja:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete org ids for users that are no longer active in AD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $organizations = collect([
            'Kinda',
            'Boxholm',
            'Ödeshög',
            'Ydre',
            'Åtvidaberg',
            'Vimmerby',
            'Itsam',
        ]);

        foreach($organizations as $organization) {
            $this->info('Checking '.$organization.'.');
            $orgids = $this->getOrgidsPerOrganization($organization);
            foreach($orgids as $orgid) {
                $username = $orgid->organisationId->identifier;
                $this->info('    '.$username);
                $aduser = User::where('sAMAccountName', $username)->first();
                if($aduser === null) {
                    $this->info("FINNS INTE!");
                    $this->removeOrgId($username, $organization);
                    continue;
                }

                $uac = new AccountControl(
                    $aduser->getFirstAttribute('userAccountControl')
                );
                
                if ($uac->has(AccountControl::ACCOUNTDISABLE)) {
                    $this->info("INAKTIVERAD!");
                    $this->removeOrgId($username, $organization);
                    continue;
                }

            }
        }

        return Command::SUCCESS;
    }
}
