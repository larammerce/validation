<?php

namespace Larammerce\Validation\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Larammerce\AnnotationParser\{
    ReflectiveMethod,
    AnnotationNotFoundException
};
use Closure;

class RulesMiddleware
{
    public function handle(Request $request, Closure $next, string $guard)
    {
        $reflective_method = ReflectiveMethod::withAction($request->route()->getActionName())->getMethod();
        try {
            $rules = $reflective_method->getAnnotation("rules")->getProperties();
            if (isset($rules["dynamic_rules"])) {
                $dynamic_rules = $rules["dynamic_rules"];
                unset($rules["dynamic_rules"]);
                $rules = array_merge($rules, $dynamic_rules);
            }
        } catch (AnnotationNotFoundException $e) {
            //if there is no @rules annotation, it means there is no need to validation.
            return $next($request);
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->needsJson()) {
                /*return ResponseFactory::jsonResponse(
                    $validator->messages()->toArray(),
                    400,
                    [
                        "request_data" => $request->all()
                    ]
                );*/
            } else {
                // return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        return $next($request);
    }
}
