<?php

namespace Tests;

/**
 * Class BaseTenantTest
 *
 * @package Tests\Feature\Tenants
 */
class BaseTenantTest extends TestCase
{
    /**
     * Visit the given URI with a GET request.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function get($uri, array $headers = [])
    {
        return parent::get('http://tenant_test.scool.acacha.test/' . $uri,$headers);
    }

    /**
     * Call the given URI with a JSON request.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function json($method, $uri, array $data = [], array $headers = [])
    {
        return parent::json($method, 'http://tenant_test.scool.acacha.test/' . $uri, $data, $headers);
    }

}