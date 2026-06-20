<?php

namespace GuljahanG\SwagLite\Services;

use ReflectionMethod;
use Illuminate\Foundation\Http\FormRequest;

class RequestAnalyzer
{
    public function analyze(
        string $controller,
        string $method
    ): array {

        $reflection = new ReflectionMethod(
            $controller,
            $method
        );

        foreach (
            $reflection->getParameters()
            as $parameter
        ) {

            $type = $parameter->getType();

            if (!$type) {
                continue;
            }

            $className = $type->getName();

            if (
                is_subclass_of(
                    $className,
                    FormRequest::class
                )
            ) {

                $request = new $className();

                return $request->rules();
            }
        }

        return [];
    }
}