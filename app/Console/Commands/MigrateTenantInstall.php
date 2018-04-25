<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\MigrateTenants;
use Illuminate\Console\Command;

/**
 * Class MigrateTenantInstall.
 *
 * @package App\Console\Commands
 */
class MigrateTenantInstall extends Command
{
    use MigrateTenants;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant:install {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the migration repository in tenant database';

    /**
     * Command to execute.
     *
     * @return string
     */
    private function command() {
        return 'migrate:install';
    }

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
     * @return mixed
     */
    public function handle()
    {
        $this->configureTenant();
        $this->call($this->command(), [
            '--database' => 'tenant'
        ]);
    }
}
