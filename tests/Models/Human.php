<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\Hydrator;

class Human extends Hydrator
{
    public string $name;
    public int $age;
    public float $money;
    public bool $male;
    public array $items;
    public ?Car $car;
    public ?Human $mother;
}
