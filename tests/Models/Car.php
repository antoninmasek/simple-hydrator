<?php

namespace AntoninMasek\SimpleHydrator\Tests\Models;

use AntoninMasek\SimpleHydrator\Attributes\Collection;
use DateTime;

class Car
{
    public string $type;

    public string $brand;

    public ?ClassThatNeedsCustomCaster $customCaster;

    #[Collection(Key::class)]
    public ?array $keys;

    #[Collection(\DateTime::class)]
    public ?array $serviceAppointments;
}
