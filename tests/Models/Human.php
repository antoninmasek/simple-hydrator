<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\DataObject;
use DateTime;

class Human extends DataObject
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
