<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\MigrateTenants;
use Illuminate\Console\Command;

/**
 * Class MigrateTenantFresh.
 *
 * @package App\Console\Commands
 */
class MigrateTenantFresh extends Command
{
    use MigrateTenants;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant:fresh {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migrations in tenant database';

    /**
     * Command to execute.
     *
     * @return string
     */
    private function command() {
        return 'migrate:fresh';
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
        $this->run_migration_command();
    }
}
