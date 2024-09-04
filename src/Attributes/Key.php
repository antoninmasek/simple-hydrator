<?php

namespace AntoninMasek\SimpleHydrator\Attributes;

use Attribute;

#[Attribute]
class Key
{
    public function __construct(public string $key) {}
}
