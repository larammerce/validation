<?php

namespace Larammerce\Validation\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Larammerce\AnnotationParser\{
    ReflectiveMethod,
    AnnotationNotFoundException,
    AnnotationBadActionPassedException
};
use Closure;

class RulesMiddleware
{
    public function handle(Request $request, Closure $next, string $guard)
    {
        $reflective_method = null;

        try {
            $reflective_method = ReflectiveMethod::withAction($request->route()->getActionName())->getMethod();
        } catch (AnnotationBadActionPassedException $e) {
            return $next($request);
        }

        $annotation_name = config("larammerce_validation.annotation_name");
        $dynamic_rules_key = config("larammerce_validation.dynamic_rules_key");

        try {
            $rules = $reflective_method->getAnnotation(
                $annotation_name
            )->getProperties();
            if (isset($rules[$dynamic_rules_key])) {
                $dynamic_rules = $rules[$dynamic_rules_key];
                unset($rules[$dynamic_rules_key]);
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
                );*/ } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        return $next($request);
    }
}
