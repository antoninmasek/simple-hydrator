<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

class Car
{
    public string $type;
    public string $brand;
    public ?ClassThatNeedsCustomCaster $customCaster;
}
