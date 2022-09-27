<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Traits\FrejaAPI;
//use LdapRecord\Models\ActiveDirectory\User;
//use LdapRecord\Models\Attributes\AccountControl;
//use LdapRecord\Models\ActiveDirectory\Group;

class FrejaFixDeletedOrgIds extends Command
{
    use FrejaAPI;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freja:undelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create org-ids for a lot of users at once';

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
        logger("Återskapar en massa tjänste-ID");

        $allDeleted = [
            'user1',
            'user2',
        ];

        foreach($allDeleted as $deleted) {
            $this->info($deleted);
            $user = new User($deleted);
            $user->organization = $user->organizations[0];
            logger(print_r($user, true));
            $response = $this->addOrgId($user);
            logger(print_r($response, true));
            $this->info($response);
        }

        return Command::SUCCESS;
    }
}
