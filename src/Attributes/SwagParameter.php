<?php

namespace GuljahanG\SwagLite\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class SwagParameter
{
    public function __construct(
        public string $name,
        public string $in = 'query', // path, query, body, header
        public bool $required = false,
        public ?string $description = null,
        public mixed $example = null,
    ) {}
}