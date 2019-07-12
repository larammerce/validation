<?php

namespace Larammerce\Validation\Tests\Controllers;

use Illuminate\Routing\Controller;

/**
 * @package Larammerce\Validation\Tests\Controllers
 */
class FooBar extends Controller {
    /**
     * @rules(title="required", email="required|email")
     */
    public function fooBar(){
        return "Hello World !";
    }
}
