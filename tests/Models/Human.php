<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\Hydrator;
use DateTime;

class Human extends Hydrator
{
    public string $name;
    public int $kids;
    public DateTime $dateOfBirth;
    public float $money;
    public bool $male;
    public array $items;
    public ?Car $car;
    public ?Human $mother;
}
