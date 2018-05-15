<?php

use App\Http\Resources\UserResource;
use App\Models\AdministrativeStatus;
use App\Models\Family;
use App\Models\Force;
use App\Models\Menu;
use App\Models\Name;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\User;
use App\Models\UserType;
use App\Tenant;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Create model.
 *
 * @param $class
 * @param array $attributes
 * @param int $times
 * @return mixed
 */
function create($class, $attributes = [], $times = 1)
{
    $data = factory($class)->times($times)->create($attributes);
    if ($times > 1) {
        return $data;
    }
    return $data->first();
}

if (! function_exists('scool_menu')) {

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    function scool_menu()
    {
        return Menu::all();
    }
}

if (! function_exists('create_tenant')) {

    /**
     * Create Tenant.
     *
     * @param $name
     * @param $subdomain
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    function create_tenant($name,$subdomain)
    {
        return Tenant::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'hostname' => 'localhost',
            'username' => $subdomain,
            'password' => 'secret',
            'database' => $subdomain,
            'port' => 3306
        ]);
    }
}

if (! function_exists('tenant_connect')) {
    /**
     * Establish a tenant database connection.
     *
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     */
    function tenant_connect($hostname, $username, $password, $database)
    {
        // Erase the tenant connection, thus making Laravel get the default values all over again.
        DB::purge('tenant');

        // Make sure to use the database name we want to establish a connection.
        Config::set('database.connections.tenant.host', $hostname);
        Config::set('database.connections.tenant.database', $database);
        Config::set('database.connections.tenant.username', $username);
        Config::set('database.connections.tenant.password', $password);

        // Rearrange the connection data
        DB::reconnect('tenant');

        // Ping the database. This will throw an exception in case the database does not exists.
        Schema::connection('tenant')->getConnection()->reconnect();
    }
}

if (! function_exists('main_connect')) {
    function main_connect()
    {
        // Erase the tenant connection, thus making Laravel get the default values all over again.
        DB::purge('tenant');

        Config::set('database.default',env('DB_CONNECTION', 'mysql'));

        // Ping the database. This will throw an exception in case the database does not exists.
        Schema::connection(config('database.default'))->getConnection()->reconnect();
    }
}

if (! function_exists('create_admin_user_on_tenant')) {
    /**
     * @param $user
     * @param $tenant
     */
    function create_admin_user_on_tenant($user, $tenant, $password = null)
    {
        tenant_connect(
            $tenant->hostname,
            $tenant->username,
            $tenant->password,
            $tenant->database
        );

        if(!$password) $password = str_random();

        $existingUser = App\Models\User::where('email',$user->email)->first();

        if (!$existingUser) {
            User::forceCreate([
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt($password),
                'admin' => true
            ]);
        }
        DB::purge('tenant');
    }
}

if (! function_exists('create_admin_user')) {
    /**
     *
     */
    function create_admin_user()
    {
        if (! App\User::where('email',env('ADMIN_USER_EMAIL','sergiturbadenas@gmail.com'))->first()) {
            App\User::forceCreate([
                'name' => env('ADMIN_USER_NAME','Sergi Tur Badenas'),
                'email' => env('ADMIN_USER_EMAIL','sergiturbadenas@gmail.com'),
                'password' => bcrypt(env('ADMIN_USER_PASSWORD','123456')),
                'admin' => true
            ]);
        }
    }
}

if (! function_exists('create_default_tenant')) {
    function create_default_tenant() {
        $user = App\User::find(1);
        $tenant = Tenant::where('subdomain','iesebre')->first();
        if (! $tenant) {
            $tenant = $user->addTenant($tenant = Tenant::create([
                'name' => "Institut de l'Ebre",
                'subdomain' => 'iesebre',
                'hostname' => 'localhost',
                'database' => 'iesebre',
                'username' => 'iesebre',
                'password' => str_random(),
                'port' => 3306
            ]));
        }

        create_mysql_full_database(
            $tenant->database,
            $tenant->username ,
            $tenant->password,
            $tenant->hostname);

        create_admin_user_on_tenant($tenant->user, $tenant, env('ADMIN_USER_PASSWORD','123456'));

        DB::purge('tenant');

        main_connect();
    }
}


if (! function_exists('create_admin_user_on_subdomain')) {

    /**
     * @param $subdomain
     */
    function create_admin_user_on_subdomain($subdomain)
    {
        $tenant = Tenant::findBySubdomain($subdomain);
        tenant_connect(
            $tenant->hostname,
            $tenant->username,
            $tenant->password,
            $tenant->database
        );

        User::forceCreate([
            'name' => env('ADMIN_USER_NAME_ON_TENANT','Sergi Tur Badenas'),
            'email' => env('ADMIN_USER_EMAIL_ON_TENANT','sergiturbadenas@gmail.com'),
            'password' => bcrypt(env('ADMIN_USER_PASSWORD_ON_TENANT','123456')),
            'admin' => true
        ]);
    }
}

if (! function_exists('save_current_tenant_config')) {
    /**
     * @return object
     */
    function save_current_tenant_config()
    {
        $host = Config::get('database.connections.tenant.host');
        $database = Config::get('database.connections.tenant.database');
        $username = Config::get('database.connections.tenant.username');
        $password = Config::get('database.connections.tenant.password');

        return (object) compact('host', 'database', 'username', 'password');
    }
}

if (! function_exists('restore_current_tenant_config')) {
    /**
     * @param $oldConfig
     */
    function restore_current_tenant_config($oldConfig)
    {
        Config::set('database.connections.tenant.host', $oldConfig->host);
        Config::set('database.connections.tenant.database', $oldConfig->database);
        Config::set('database.connections.tenant.username', $oldConfig->username);
        Config::set('database.connections.tenant.password', $oldConfig->password);
    }
}

if (! function_exists('test_user')) {
    /**
     * @param $user
     * @param $tenant
     * @return array
     */
    function test_user($user, $tenant, $password) {
        $current_config = save_current_tenant_config();
        $result = [];
        try {
            tenant_connect($tenant->hostname, $tenant->username, $tenant->password, $tenant->database);

            $tenantUser = User::where('email',$user->email)->firstOrFail();

            if (Hash::check($password, $tenantUser->password)) {
                $result = [ 'connection' => 'ok' ];
            } else {
                $result = [
                    'connection' => 'Error',
                    'exception' => 'Password incorrect for user ' . $user->email
                ];
            }


        } catch (PDOException $e) {
            $result = [
                'connection' => 'Error',
                'exception' => $e->getMessage()
            ];
        }

        restore_current_tenant_config($current_config);
        return $result;
    }
}

if (! function_exists('test_connection')) {
    /**
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     * @return array
     */
    function test_connection($hostname, $username, $password, $database)
    {
        $current_config = save_current_tenant_config();
        $result = [];
        try {
            tenant_connect($hostname, $username, $password, $database);
            $result = [ 'connection' => 'ok' ];
        } catch (PDOException $e) {
            $result = [
                'connection' => 'Error',
                'exception' => $e->getMessage()
            ];
        }
        restore_current_tenant_config($current_config);

        return $result;
    }
}


if (! function_exists('tenant_migrate')) {
    /**
     * Run Tenant Migrations in the connected tenant database.
     */
    function tenant_migrate()
    {
        Config::set('auth.providers.users.model',User::class);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant'
        ]);
    }
}

if (! function_exists('tenant_seed')) {
    /**
     * Run Tenant Migrations in the connected tenant database.
     */
    function tenant_seed()
    {
        Config::set('auth.providers.users.model',User::class);

        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => 'TenantDatabaseSeeder'
        ]);
    }
}

/**
 * @param $name
 * @param $user
 * @param null $password
 * @return null|string
 */
function create_mysql_full_database($name, $user , $password = null, $host = null)
{
    create_mysql_database($name);
    $password = create_mysql_user($user, $password, $host);
    mysql_grant_privileges($user, $name, $host);

    $hostname = 'localhost';
    if ($host) $hostname = $host;
    tenant_connect($hostname, $name, $password, $name);
    tenant_migrate();
    tenant_seed();

    return $password;
}

if (! function_exists('tenant_connect_migrate_seed')) {

    /**
     * @param $name
     */
    function tenant_connect_migrate_seed($name)
    {
        $tenant = Tenant::findBySubDomain($name);
        tenant_connect($tenant->hostname, $tenant->subdomain, $tenant->password, $tenant->subdomain);
        tenant_migrate();
        tenant_seed();
    }
}

/**
 * @param $name
 * @param $user
 * @param null $password
 * @return null|string
 */
function delete_mysql_full_database($name, $user, $host = null)
{
    delete_mysql_database($name);
    delete_mysql_user($user, $host);
}

/**
 *
 */
function set_mysql_admin_connection() {
    DB::purge('mysql');

    Config::set('database.connections.mysql.host', env('MYSQL_ADMIN_HOST'));
    Config::set('database.connections.mysql.port', env('MYSQL_ADMIN_PORT'));
    Config::set('database.connections.mysql.database', null);
    Config::set('database.connections.mysql.username', env('MYSQL_ADMIN_USERNAME'));
    Config::set('database.connections.mysql.password', env('MYSQL_ADMIN_PASSWORD'));

    // Rearrange the connection data
    DB::reconnect('mysql');

    // Ping the database. This will throw an exception in case the database does not exists.
    Schema::connection('mysql')->getConnection()->reconnect();
}

/**
 * @param $name
 */
function create_mysql_database($name)
{
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `{$name}`");
}

/**
 * @param $name
 */
function delete_mysql_database($name)
{
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec("DROP DATABASE IF EXISTS `{$name}`");
}

/**
 * @param $name
 */
function remove_mysql_database($name)
{
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec("DROP DATABASE IF EXISTS `{$name}`");
}

/**
 * @param $name
 * @param null $password
 * @param string $host
 * @return null|string
 */
function create_mysql_user($name, $password = null, $host = 'localhost')
{
    set_mysql_admin_connection();
    if(!$password) $password = str_random();
    DB::connection('mysql')->getPdo()->exec(
        "CREATE USER IF NOT EXISTS '{$name}'@'{$host}'");
    DB::connection('mysql')->getPdo()->exec(
        "ALTER USER '{$name}'@'{$host}' IDENTIFIED BY '{$password}'");
    return $password;
}

/**
 * @param $name
 * @param null $password
 * @param string $host
 * @return null|string
 */
function delete_mysql_user($name, $host = 'localhost')
{
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec(
        "DROP USER IF EXISTS '{$name}'@'{$host}'");
}

/**
 * @param $user
 * @param string $host
 */
function mysql_grant_all_privileges($user, $host = 'localhost') {
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec(
        "GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'{$host}' WITH GRANT OPTION");
    DB::connection('mysql')->getPdo()->exec("FLUSH PRIVILEGES");
}

/**
 * @param $user
 * @param $database
 * @param string $host
 */
function mysql_grant_privileges($user, $database, $host = 'localhost') {
    set_mysql_admin_connection();
    DB::connection('mysql')->getPdo()->exec(
        "GRANT ALL PRIVILEGES ON {$database}.* TO '{$user}'@'{$host}' WITH GRANT OPTION");
    DB::connection('mysql')->getPdo()->exec("FLUSH PRIVILEGES");
}

if (!function_exists('get_tenant')) {
    /**
     * @param $name
     * @return mixed
     */
    function get_tenant($name) {
        return \App\Tenant::where('subdomain', $name)->firstOrFail();
    }
}


if (!function_exists('formatted_logged_user')) {
    function formatted_logged_user()
    {
        return json_encode((new UserResource(Auth::user()))->resolve());
    }
}

if (!function_exists('initialize_tenant_roles_and_permissions')) {
    function initialize_tenant_roles_and_permissions()
    {
        $roles = [
            'Student',
            'Teacher',
            'Janitor',
            'AdministrativeAssistant',
            'Familiar',
            'Manager',
            'Admin',
            'UsersManager',
            'StaffManager',
            'TeachersManager',
            'PhotoTeachersManager'
        ];

        // Manager
        // - Rol assignat a l'usuari principal (de fet és superadmin) però també es pot assignar a altres
        // - Menú administració:
        // - Gestió de mòduls

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $permissions = [
        ];


        foreach ($permissions as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
        }
    }
}


if (!function_exists('initialize_gates')) {
    function initialize_gates()
    {
        Gate::define('show-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('create_users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('delete_users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        // STAFF
        Gate::define('show-staff', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('store-staff', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('delete-staff', function ($user) {
            return $user->hasRole('StaffManager');
        });

        //Teachers
        Gate::define('show-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('show-pending-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('delete-pending-teacher', function ($user) {
            return $user->hasRole('TeachersManager');
        });


        Gate::define('show-teachers-photos', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('store-teachers-photos', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('show-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('download-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('delete-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('delete-all-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('edit-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('store-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('list-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('destroy-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        //Pending teachers
        Gate::define('list_pending_teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });


    }
}

if (!function_exists('initialize_menus')) {
    function initialize_menus() {
        Menu::firstOrCreate([
            'icon' => 'home',
            'text' => 'Principal',
            'href' => '/home'
        ]);

        Menu::firstOrCreate([
            'heading' => 'Administració',
            'role' => 'Manager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Mòduls',
            'href' => '/modules',
            'role' => 'Manager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Usuaris',
            'href' => '/users',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Plantilla',
            'href' => '/staff',
            'role' => 'StaffManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Professorat',
            'href' => '/teachers',
            'role' => 'TeachersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Fotos Professorat',
            'href' => '/teachers_photos',
            'role' => 'PhotoTeachersManager'
        ]);


        Menu::firstOrCreate([
            'text' => 'Configuració general',
            'href' => '/config',
            'role' => 'Admin'
        ]);
    }
}



if (!function_exists('initialize_staff_types')) {
    function initialize_staff_types()
    {
        StaffType::firstOrCreate([
            'name' => 'Professor/a'
        ]);

        StaffType::firstOrCreate([
            'name' => 'Conserge'
        ]);

        StaffType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);
    }
}

if (!function_exists('initialize_users')) {
    function initialize_users()
    {

    }
}

if (!function_exists('collect_files')) {
    /**
     * Collect files.
     *
     * @param $path
     * @param string $disk
     * @return static
     */
    function collect_files($path, $disk = 'local')
    {
        $files = collect(File::allFiles(Storage::disk($disk)->path($path)))->map(function ($file) {
            return [
                'filename' => $filename = $file->getFilename(),
                'slug' => str_slug($filename,'-')
            ];
        });
        return $files;
    }
}

if (!function_exists('initialize_administrative_assistants')) {
    function initialize_administrative_assistants()
    {
        User::createIfNotExists([
            'name' => 'Pilar Vericat',
            'email' => 'pilarvericat@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Pilar',
                'sn1' => 'Vericat',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Administratiu/va')->id,
                    'code' => 'A1'
                ])
            );

        User::createIfNotExists([
            'name' => 'Cinta Tomas',
            'email' => 'cintatomas@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Cinta',
                'sn1' => 'Tomas',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Administratiu/va')->id,
                    'code' => 'A2'
                ])
            );

        User::createIfNotExists([
            'name' => 'Lluïsa Garcia',
            'email' => 'lluisagarcia@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Lluisa',
                'sn1' => 'Garcia',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Administratiu/va')->id,
                    'code' => 'A3'
                ])
            );

        User::createIfNotExists([
            'name' => 'Sonia Alegria',
            'email' => 'soniaalegria@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Sonia',
                'sn1' => 'Alegria',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Administratiu/va')->id,
                    'code' => 'F4'
                ])
            );
    }
}

if (!function_exists('initialize_janitors')) {
    function initialize_janitors()
    {
        User::createIfNotExists([
            'name' => 'Jaume Benaiges',
            'email' => 'jaumebenaiges@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Jaume',
                'sn1' => 'Benaiges',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Conserge')->id,
                    'code' => 'C1'
                ])
            );

        User::createIfNotExists([
            'name' => 'Jordi Caudet',
            'email' => 'jordicaudet@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Jordi',
                'sn1' => 'Caudet',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Conserge')->id,
                    'code' => 'C2'
                ])
            );

        User::createIfNotExists([
            'name' => 'Leonor Agramunt',
            'email' => 'leonoragramunt@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName(Name::firstOrCreate([
                'givenName' => 'Leonor',
                'sn1' => 'Agramunt',
                'sn2' => '',
            ]))
            ->assignStaff(
                Staff::firstOrCreate([
                    'type_id' => StaffType::findByName('Conserge')->id,
                    'code' => 'C3'
                ])
            );
    }
}


if (!function_exists('initialize_teachers')) {
    function initialize_teachers()
    {
        User::createIfNotExists([
            'name' => 'Dolors Sanjuan Aubà',
            'code' => '02',
            'email' => 'dolorssanjuanauba@iesebre.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
        ->assignFullName(Name::firstOrCreate([
            'givenName' => 'Dolors',
            'sn1' => 'Sanjuan',
            'sn2' => 'Aubà',
        ]))
        ->assignStaff(
            Staff::firstOrCreate([
                'type_id' => StaffType::findByName('Professor/a')->id,
                'specialty_id' => Specialty::findByCode('CAS')->id,
                'family_id' => Family::findByCode('CA')->id,
                'code' => '002'
            ])
        );
        //TODO ->situation('D')->assignTeacherCode('002');
    }
}

if (!function_exists('initialize_families')) {
    function initialize_families()
    {
        Family::firstOrCreate([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);

        Family::firstOrCreate([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);

        Family::firstOrCreate([
            'name' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS'
        ]);

        Family::firstOrCreate([
            'name' => 'Arts gràfiques',
            'code' => 'ARTS'
        ]);

      
        Family::firstOrCreate([
            'name' => 'Administració i finances',
            'code' => 'ADMIN'
        ]);

        Family::firstOrCreate([
            'name' => 'Comerç i marqueting',
            'code' => 'COMERÇ'
        ]);

        Family::firstOrCreate([
            'name' => 'Electricitat i electrònica/ Energia i aigua',
            'code' => 'ELECTRIC'
        ]);

        Family::firstOrCreate([
            'name' => 'Fabricació mecànica/ Instal·lació i manteniment',
            'code' => 'FABRIC'
        ]);

        Family::firstOrCreate([
            'name' => 'Edificació i obra civil',
            'code' => 'EDIFIC'
        ]);

        Family::firstOrCreate([
            'name' => 'Cursos d’accés',
            'code' => 'CA'
        ]);

        Family::firstOrCreate([
            'name' => 'Departament de llengües estrangeres',
            'code' => 'ESTRANGER'
        ]);

        Family::firstOrCreate([
            'name' => 'FOL',
            'code' => 'FOL'
        ]);
      
    }
}

if (!function_exists('initialize_forces')) {
    function initialize_forces()
    {
        Force::firstOrCreate([
            'name' => 'Mestres',
            'code' => 'MESTRES'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        Force::firstOrCreate([
            'name' => 'Professors tècnics de formació professional',
            'code' => 'PT'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'escoles oficials d'idiomes",
            'code' => 'IDIOMES'
        ]);
    }
}



if (!function_exists('initialize_administrative_statuses')) {
    function initialize_administrative_statuses()
    {
        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a amb plaça definitiva'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a propietari provisional'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a en pràctiques'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Comissió de serveis'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Interí/na'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Substitut/a'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Expert/a'
        ]);
    }
}

if (!function_exists('initialize_specialities')) {
    function initialize_specialities()
    {
        // Sanitat
        Specialty::firstOrCreate([
            'code' => '517',
            'name' => 'Processos diagnòstics clínics i productes ortoprotètics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '518',
            'name' => 'Processos sanitaris',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        // Cos -> Professors tècnics de formació professional
        Specialty::firstOrCreate([
            'code' => '619',
            'name' => 'Procediments de diagnòstic clínic i productes ortoprotètics',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        // Cos -> Professors tècnics de formació professional
        Specialty::firstOrCreate([
            'code' => '620',
            'name' => 'Procediments sanitaris i assistencials ',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        // Serveis socioculturals i a la comunitat
        Specialty::firstOrCreate([
            'code' => '508',
            'name' => 'Intervenció sociocomunitària',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SERVEIS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '625',
            'name' => 'Serveis a la comunitat',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SERVEIS')->id
        ]);

        // Administració i finances
        Specialty::firstOrCreate([
            'code' => '501',
            'name' => 'Administració d’Empreses',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ADMIN')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '622',
            'name' => 'Processos de Gestió Administrativa',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ADMIN')->id
        ]);

        // Comerç i marqueting
        Specialty::firstOrCreate([
            'code' => '510',
            'name' => 'Organització i gestió comercial',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ADMIN')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '621',
            'name' => 'Processos comercials',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ADMIN')->id
        ]);

        // Informática
        Specialty::firstOrCreate([
            'code' => '507',
            'name' => 'Informàtica',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('INF')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '627',
            'name' => 'Sistemes i aplicacions informàtiques',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('INF')->id
        ]);

        // Electricitat i electrònica/ Energia i aigua
        Specialty::firstOrCreate([
            'code' => '524',
            'name' => 'Sistemes electrònics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '525',
            'name' => 'Sistemes electrònics i automàtics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '513',
            'name' => 'Organització i projectes de sistemes energètics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '602',
            'name' => 'Equips electrònics',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '605',
            'name' => 'Instal·lació i manteniment d’equips tèrmics i de fluids',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '606',
            'name' => 'Instal·lacions electrotècniques',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id
        ]);

        // Fabricació mecànica/ Instal·lació i manteniment
        Specialty::firstOrCreate([
            'code' => '512',
            'name' => 'Organització i projectes de fabricació mecànica',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('FABRIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '611',
            'name' => 'Mecanització i manteniment de màquines',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('FABRIC')->id
        ]);

        // Arts gràfiques
        Specialty::firstOrCreate([
            'code' => '522',
            'name' => "Processos i productes d'arts gràfiques.",
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ARTS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '623',
            'name' => 'Producció en arts gràfiques',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ARTS')->id
        ]);


        // Edificació i obra civil
        Specialty::firstOrCreate([
            'code' => '504',
            'name' => 'Construccions civils i edificació',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('EDIFIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '612',
            'name' => 'Oficina de projectes de construcció',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('EDIFIC')->id
        ]);

        // Cursos d’accés
        // Cos -> Secundària
        Specialty::firstOrCreate([
            'code' => 'MA',
            'name' => 'Matemàtiques',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('CA')->id
        ]);

        Specialty::firstOrCreate([
            'code' => 'CAS',
            'name' => 'Castellà',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('CA')->id
        ]);

        // Departament de llengües estrangeres
        // Cos -> Secundària
        Specialty::firstOrCreate([
            'code' => 'AN',
            'name' => 'Àngles',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ESTRANGER')->id
        ]);


        // FOL
        Specialty::firstOrCreate([
            'code' => '505',
            'name' => 'Formació i orientació laboral',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('FOL')->id
        ]);

    }
}



if (!function_exists('initialize_user_types')) {
    function initialize_user_types()
    {
        $teacher = UserType::firstOrCreate([
            'name' => 'Professor/a'
        ]);
        $teacher->roles()->save(Role::findByName('Teacher'));

        $student = UserType::firstOrCreate([
            'name' => 'Alumne/a'
        ]);
        $student->roles()->save(Role::findByName('Student'));

        UserType::firstOrCreate([
            'name' => 'Conserge'
        ]);

        UserType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);

        UserType::firstOrCreate([
            'name' => 'Familiars'
        ]);
    }
}

if (!function_exists('apply_tenant')) {
    function apply_tenant($name)
    {
        if ($tenant = get_tenant($name)) {
            $tenant->connect();
            $tenant->configure();
            Config::set('database.default', 'tenant');
        } else {
            dump('Tenant not found!');
        }
    }
}

if (!function_exists('add_fake_pending_teacher')) {
    function add_fake_pending_teacher()
    {
        return PendingTeacher::create([
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '84008343S',
            'birthdate' => '1980-02-04',
            'street' => 'Alcanyiz',
            'number' => 40,
            'floor' => 3,
            'floor_number' => 1,
            'postal_code' => 43500,
            'locality' => 'TORTOSA',
            'province' => 'TARRAGONA',
            'email' => 'pepe@pardo.com',
            'other_emails' => 'pepepardojeans@gmail.com,ppardo@xtec.cat',
            'telephone' => '679852467',
            'other_telephones' => '977854265,689578458',
            'degree' => 'Enginyer en chapuzas varias',
            'other_degrees' => 'Master emerito por la Juan Carlos Primero',
            'languages' => 'Suajili',
            'profiles' => 'Master of the Universe',
            'other_training' => 'Fuster',
            'force_id' => 1,
            'specialty_id' => 1,
            'teacher_start_date' => '2015',
            'start_date' => '2017-03-06',
            'opositions_date' => '2009-06-10',
            'administrative_status_id' => 1,
            'destination_place' => 'La Seu Urgell',
            'teacher_id' => 1
        ]);
    }
}
