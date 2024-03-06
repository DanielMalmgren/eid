<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\FrejaAPI;
use LdapRecord\Models\ActiveDirectory\User;

class FrejaRemoveOrgId extends Command
{
    use FrejaAPI;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freja:remove {username} {organization}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete org id for a specific user';

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
        $this->removeOrgId($this->argument('username'), $this->argument('organization'));

        return Command::SUCCESS;
    }
}
