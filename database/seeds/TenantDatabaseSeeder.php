<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Class TenantDatabaseSeeder
 */
class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        initialize_menus();
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        create_admin_user();
    }
}
