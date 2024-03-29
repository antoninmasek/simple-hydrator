<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\Attributes\Collection;

class Car
{
    public string $type;

    public string $brand;

    public ?Color $color;

    public ?int $maxSpeed = 120;

    public ?ClassThatNeedsCustomCaster $customCaster;

    #[Collection(Key::class)]
    public ?array $keys;

    #[Collection(\DateTime::class)]
    #[\AntoninMasek\SimpleHydrator\Attributes\Key('service Appointments')]
    public ?array $serviceAppointments;
}
