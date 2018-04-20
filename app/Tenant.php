<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tenant.
 *
 * @package App
 */
class Tenant extends Model
{
    public $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Establish a connection with the tenant's database.
     */
    public function connect()
    {
        if (! $this->connected()) {
            tenant_connect(
                $this->hostname,
                $this->username,
                $this->password,
                $this->database
            );
        }
    }

    public function configure()
    {
        Config::set('app.name', $this->name);
    }

    /**
     * Check if the current tenant connection settings matches the company's database settings.
     *
     * @return bool
     */
    private function connected()
    {
        $connection = Config::get('database.connections.tenant');

        return $connection['username'] == $this->username &&
            $connection['password'] == $this->password &&
            $connection['database'] == $this->database;
    }
}
