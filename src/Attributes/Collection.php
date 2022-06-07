<?php

namespace AntoninMasek\SimpleHydrator\Attributes;

use Attribute;

#[Attribute]
class Collection
{
    public function __construct(public string $class_name)
    {
    }
}
