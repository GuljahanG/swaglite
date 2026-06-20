<?php

namespace GuljahanG\SwagLite\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class SwagTag
{
    public function __construct(
        public string $name
    ) {}
}