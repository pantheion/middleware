<?php

namespace Pantheion\Middleware;

use Pantheion\Http\Request;

abstract class Middleware
{
    protected $except = [];

    public abstract function run(Request $request, \Closure $next);
}