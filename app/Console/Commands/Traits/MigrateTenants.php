<?php

namespace App\Console\Commands\Traits;

use App\Tenant;
use Config;

/**
 * Trait MigrateTenants.
 *
 * @package App\Console\Commands\Traits
 */
trait MigrateTenants
{
    /**
     * Configure tenant.
     */
    private function configureTenant() {
        $tenant = Tenant::where('subdomain',$this->argument('name'))->firstOrFail();
        $tenant->connect();
        $tenant->configure();
        Config::set('database.default', 'tenant');
    }

    private function run_migration_command() {
        $this->call($this->command(), [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant'
        ]);
    }
}