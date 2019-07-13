<?php

namespace Larammerce\Validation\Tests\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\Validation\Tests\Http\Controllers
 */
class FooBar extends Controller
{
    /**
     * @rules(title="required", auth_type="required|in:1,2", 
     *     dynamic_rules=Larammerce\Validation\Tests\Http\Controllers\FooBar::dynamicRules(request("auth_type")))
     * @param Request $request 
     * @return Response
     */
    public function fooBar(Request $request): Response
    {
        dd($request->all());
        return response()->make("Hello World !");
    }

    /**
     * @param string auth_type
     * @return array
     */
    public static function dynamicRules(?string $auth_type): array
    {
        if($auth_type === "1"){
            return [
                "email" => "required|email"
            ];
        }else if ($auth_type === "2"){
            return [
                "mobile" => "required"
            ];
        }else {
            return [];
        }
    }
}
