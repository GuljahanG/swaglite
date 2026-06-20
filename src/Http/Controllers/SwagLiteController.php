<?php

namespace GuljahanG\SwagLite\Http\Controllers;

use GuljahanG\SwagLite\Services\RouteScanner;
use GuljahanG\SwagLite\Services\RequestAnalyzer;

class SwagLiteController
{
    public function index(RouteScanner $scanner, RequestAnalyzer $analyzer)
    {

        $routes = $scanner->scan();

        return view(
            'swaglite::index',
            compact('routes')
        );
    }
}