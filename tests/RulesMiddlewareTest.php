<?php

namespace Larammerce\Validation\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Larammerce\Validation\Middleware\RulesMiddleware;
use Larammerce\Validation\ValidationServiceProvider;

class RulesMiddlewareTest extends TestCase
{
    public function test_handle_web()
    {
        Route::get("foo/bar")->uses("Larammerce\\Validation\\Tests\\Controllers\\FooBar@fooBar")->middleware(RulesMiddleware::class);

        $this->assertEquals("Hello World !", Route::dispatch(
            Request::create("/foo/bar?title=test&email=a.khajelou@gmail.com", "GET")
        )->getContent());

        $this->assertEquals(200, Route::dispatch(
            Request::create("/foo/bar?title=test&email=a.khajelou@gmail.com", "GET")
        )->getStatusCode());

        $this->assertEquals(302, Route::dispatch(
            Request::create("/foo/bar?title=test&email=test_input", "GET")
        )->getStatusCode());
    }

    public function test_handle_api()
    { 
        Route::get("foo/bar")->uses("Larammerce\\Validation\\Tests\\Controllers\\FooBar@fooBar")->middleware(RulesMiddleware::class);
        $request = Request::create("/foo/bar?title=test&email=a.khajelou@gmail.com", "GET");
        $request->headers->set("Accept", "application/json");
        
        $this->assertEquals("Hello World !", Route::dispatch($request)->getContent());

        $this->assertEquals(200, Route::dispatch($request)->getStatusCode());

        $request = Request::create("/foo/bar?title=test&email=test_input", "GET");
        $request->headers->set("Accept", "application/json");

        $this->assertEquals(400, Route::dispatch($request)->getStatusCode());
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true');
    }

    protected function getPackageProviders($app)
    {
        return [ValidationServiceProvider::class];
    }
}
