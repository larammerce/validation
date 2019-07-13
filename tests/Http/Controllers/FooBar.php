<?php

namespace Larammerce\Validation\Tests\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\Validation\Tests\Http\Controllers
 */
class FooBar extends Controller
{
    /**
     * @rules(title="required", auth_type="required|in:1,2", 
     *     dynamic_rules=Larammerce\Validation\Tests\Http\Controllers\FooBar::dynamicRules())
     * @param Request $request 
     * @return Response
     */
    public function fooBar(Request $request): Response
    {
        return response()->make("Hello World !");
    }

    /**
     * @return array
     */
    public static function dynamicRules(): array
    {
        return [
            "email" => "required|email"
        ];
    }
}
