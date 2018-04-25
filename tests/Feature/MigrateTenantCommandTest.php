<?php

namespace Tests\Feature;

use Artisan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class MigrateTenantCommandTest.
 *
 * @package Tests\Feature
 */
class MigrateTenantCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_migrate_tenant()
    {
        $this->artisan('migrate:tenant', [
            'name' => 'testacme'
        ]);

        $this->assertContains('TODO', Artisan::output());
    }

    /** @test */
    public function cannot_migrate_tenant_without_arguments()
    {
        try {
            $this->artisan('migrate:tenant');
        } catch (\Symfony\Component\Console\Exception\RuntimeException $re) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Migrating tenant without arguments did not throw a RuntimeException.");

    }

    /** @test */
    public function cannot_migrate_tenant_without_correct_tenant_name()
    {
        try {
            $this->artisan('migrate:tenant',[
                'name' => 'dsaasddassad'
            ]);
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Migrating tenant without correct tenant name did not throw a ModelNotFoundException.");

    }
}
