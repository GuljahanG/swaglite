<?php

namespace GuljahanG\SwagLite\Services;

use Illuminate\Support\Facades\Route;

class RouteScanner
{
    public function scan()
    {
        $analyzer = app(RequestAnalyzer::class);

        return collect(Route::getRoutes())

            ->filter(function ($route) {

                $action = $route->getActionName();

                // ignore closures
                if ($action === 'Closure') {
                    return false;
                }

                // ignore non-controller routes
                if (!str_contains($action, '@')) {
                    return false;
                }

                //  ignore vendor routes
                if (str_contains($action, 'Laravel\\') ||
                    str_contains($action, 'Sanctum') ||
                    str_contains($action, 'Ignition')
                ) {
                    return false;
                }

                // ignore SwagLite itself
                if (str_contains($action, 'SwagLite')) {
                    return false;
                }
                return true;
            })

            ->map(function ($route) use ($analyzer) {

                $action = $route->getActionName();

                [$controller, $method] = explode('@', $action);
                
                $reflection = new \ReflectionMethod(
                    $controller,
                    $method
                );


                $tag = null;
                $description = null;
                $responses = [];
                $parameters = [];

                $tagAttr = $reflection->getAttributes(
                    \GuljahanG\SwagLite\Attributes\SwagTag::class
                );
                

                if (!empty($tagAttr)) {
                    $tag = $tagAttr[0]->newInstance()->name;
                }

                $descAttr = $reflection->getAttributes(
                    \GuljahanG\SwagLite\Attributes\SwagDescription::class
                );

                if (!empty($descAttr)) {
                    $description = $descAttr[0]->newInstance()->text;
                }
                
                $responseAttrs = $reflection->getAttributes(
                    \GuljahanG\SwagLite\Attributes\SwagResponse::class
                );

                foreach ($responseAttrs as $attribute) {

                    $response =
                        $attribute->newInstance();

                    $responses[] = [
                        'status' => $response->status,
                        'description' => $response->description,
                        'example' => $response->example,
                    ];
                }

                $paramAttrs = $reflection->getAttributes(
                        \GuljahanG\SwagLite\Attributes\SwagParameter::class
                );
                

                foreach ($paramAttrs as $attribute) {

                    $parameters[] = $attribute->newInstance();
                }

                $rules = $analyzer->analyze($controller, $method);

                return [
                    'id' => md5($route->methods()[0] . $route->uri()),
                    'methods' => implode(', ', $route->methods()),
                    'uri' => $route->uri(),
                    'group' => $tag,
                    'description' => $description,
                    'controller' => $controller,
                    'method' => $method,
                    'rules' => $rules,
                    'responses' => $responses,
                    'parameters' => $parameters
                ];
            })

            ->filter(fn ($route) => !empty($route['group']))
            ->values();
    }
}