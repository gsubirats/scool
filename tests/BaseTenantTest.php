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
        return parent::get($this->baseURI() . $uri,$headers);
    }

    /**
     * Visit the given URI with a POST request.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function post($uri, array $data = [], array $headers = [])
    {
        return parent::post($this->baseURI() . $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function delete($uri, array $data = [], array $headers = [])
    {
        return parent::delete($this->baseURI() . $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function put($uri, array $data = [], array $headers = [])
    {
        return parent::put($this->baseURI() . $uri, $data, $headers);
    }

    protected function baseURI()
    {
        return 'http://tenant_test.' . config('app.domain') . '/';
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
        return parent::json($method, $this->baseURI() . $uri, $data, $headers);
    }

    /** @test */
    public function test_example()
    {
        $this->assertTrue(true);
    }

}