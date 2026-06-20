<?php

namespace GuljahanG\SwagLite\Attributes;

use Attribute;

#[Attribute(
    Attribute::TARGET_METHOD |
    Attribute::IS_REPEATABLE
)]
class SwagResponse
{
    public function __construct(
        public int $status,
        public string $description,
        public mixed $example = null,
    ) {
    }
}