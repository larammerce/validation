<?php

namespace Larammerce\Validation\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Application;
use Larammerce\Validation\Middleware\RulesMiddleware;
use Larammerce\Validation\ValidationServiceProvider;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\Validation\Tests
 */
class RulesMiddlewareTest extends TestCase
{
    public function test_handle_web()
    {
        $this->assertEquals("Hello World !", Route::dispatch(
            Request::create("/foo/bar?title=test&auth_type=1&email=a.khajelou@gmail.com", "GET")
        )->getContent());

        $this->assertEquals(200, Route::dispatch(
            Request::create("/foo/bar?title=test&auth_type=1&email=a.khajelou@gmail.com", "GET")
        )->getStatusCode());

        $this->assertEquals(302, Route::dispatch(
            Request::create("/foo/bar?title=test&auth_type=1&email=test_input", "GET")
        )->getStatusCode());
    }

    public function test_handle_api()
    {
        $request = Request::create("/foo/bar?title=test&auth_type=1&email=a.khajelou@gmail.com", "GET");
        $request->headers->set("Accept", "application/json");

        $this->assertEquals("Hello World !", Route::dispatch($request)->getContent());

        $this->assertEquals(200, Route::dispatch($request)->getStatusCode());

        $request = Request::create("/foo/bar?title=test&auth_type=1&email=test_input", "GET");
        $request->headers->set("Accept", "application/json");

        $this->assertEquals(400, Route::dispatch($request)->getStatusCode());
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Route::get("foo/bar", "Larammerce\\Validation\\Tests\\Http\\Controllers\\FooBar@fooBar")->middleware(RulesMiddleware::class);
    }

    /**
     * @param Application $app
     * @return void
     */
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app["config"]->set("app.debug", "true");
    }

    protected function getPackageProviders($app)
    {
        return [ValidationServiceProvider::class];
    }
}
