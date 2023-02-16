<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\DataObject;

class Human extends DataObject
{
    public string $name;
    public ?string $first_name;
    public ?string $last_name;
    public int $kids;
    public ?\DateTime $dateOfBirth;
    public float $money;
    public bool $male;
    public array $items;
    public ?Car $car;
    public ?Human $mother;
}
