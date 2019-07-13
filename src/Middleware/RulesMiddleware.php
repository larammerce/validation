<?php

namespace Larammerce\Validation\Middleware;

use Illuminate\Http\{
    Request
};
use Illuminate\Support\Facades\Validator;
use Larammerce\AnnotationParser\{
    ReflectiveMethod,
    AnnotationNotFoundException,
    AnnotationBadActionPassedException
};
use Symfony\Component\HttpFoundation\Response;
use Closure;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\Validation\Middleware
 */
class RulesMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string[] guards
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $reflective_method = null;

        try {
            $reflective_method = ReflectiveMethod::withAction($request->route()->getActionName());
        } catch (AnnotationBadActionPassedException $e) {
            return $next($request);
        }

        $annotation_name = config("larammerce.validation.annotation_name");
        $dynamic_rules_key = config("larammerce.validation.dynamic_rules_key");

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
            if ($request->expectsJson()) {
                //replace this part with Larammerce\Http\ResponseFactory::jsonResponse() method.
                return response()->json(
                    $validator->messages()->toArray(),
                    400
                );
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        return $next($request);
    }
}
