<?php

namespace GuljahanG\SwagLite\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class SwagDescription
{
    public function __construct(
        public string $text
    ) {
    }
}