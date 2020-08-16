<?php

namespace Pantheion\Middleware;

use Pantheion\Http\Request;
use Pantheion\Http\Response;
use Pantheion\Routing\Route;

class Handler
{
    public function handle(Request $request, Route $route, \Closure $top)
    {
        $middlewares = [$top];
        
        foreach($route->middleware as $middleware) {
            $instance = new $middleware;

            if (in_array($request->path, $instance->except)) {
                continue;
            }

            $next = $middlewares[count($middlewares) - 1];
            $middlewares[] = function(Request $request) use ($instance, $next) {
                $result = $instance->run($request, $next);

                if(!is_object($result) && !($result instanceof Response)) {
                    $class = get_class($instance);
                    throw new \Exception("The Middleware {$class} doesn't return an instance of Response");
                }

                return $result;
            };
        }

        return $middlewares[count($middlewares) - 1]($request);
    }
}
