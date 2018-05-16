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
        seed_provinces();
        $this->call(LocationsTableSeeder::class);

        initialize_menus();
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        create_tenant_admin_user();
        initialize_staff_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        initialize_administrative_statuses();
        initialize_users();
        initialize_teachers();

        initialize_janitors();
        initialize_administrative_assistants();


    }
}
