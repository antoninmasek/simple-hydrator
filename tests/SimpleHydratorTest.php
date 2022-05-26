<?php

namespace AntoninMasek\SimpleHydrator\Tests;

use AntoninMasek\SimpleHydrator\Hydrator;
use AntoninMasek\SimpleHydrator\Tests\Models\Car;
use AntoninMasek\SimpleHydrator\Tests\Models\Human;
use DateTime;
use PHPUnit\Framework\TestCase;

class SimpleHydratorTest extends TestCase
{
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name'          => 'John',
            'kids'          => 0,
            'dateOfBirth'   => '1969-07-20',
            'money'         => 33.3,
            'male'          => true,
            'items'         => ['phone', 'wallet', 'keys'],
            'car'           => null,
            'mother'        => [
                'name'  => 'Jane',
                'kids'  => 2,
                'money' => 66.6,
                'male'  => false,
                'items' => ['phone', 'keys'],
                'car'   => [
                    'type'  => '911',
                    'brand' => 'Porsche',
                ],
            ],
        ];
    }

    public function testItCanHydrateObjectUsingHydrator()
    {
        $tony = Hydrator::hydrateRaw(Human::class, $this->data);

        $this->assertSame('John', $tony->name);
        $this->assertSame(0, $tony->kids);
        $this->assertTrue($tony->dateOfBirth instanceof DateTime);
        $this->assertSame(33.3, $tony->money);
        $this->assertSame(true, $tony->male);
        $this->assertCount(3, $tony->items);
        $this->assertCount(3, $tony->items);
        $this->assertSame('phone', $tony->items[0]);
        $this->assertSame('wallet', $tony->items[1]);
        $this->assertSame('keys', $tony->items[2]);
        $this->assertSame(null, $tony->car);
        $this->assertTrue($tony->mother instanceof Human);

        $mother = $tony->mother;
        $this->assertSame('Jane', $mother->name);
        $this->assertSame(2, $mother->kids);
        $this->assertSame(66.6, $mother->money);
        $this->assertSame(false, $mother->male);
        $this->assertCount(2, $mother->items);
        $this->assertSame('phone', $mother->items[0]);
        $this->assertSame('keys', $mother->items[1]);
        $this->assertTrue($mother->car instanceof Car);
        $this->assertSame(null, $mother->mother);

        $car = $mother->car;
        $this->assertSame('911', $car->type);
        $this->assertSame('Porsche', $car->brand);

        $car = Hydrator::hydrateRaw(Car::class, $this->data['mother']['car']);
        $this->assertSame('911', $car->type);
        $this->assertSame('Porsche', $car->brand);
    }

    public function testObjectCanHydrateItselfWhenExtendingHydrator()
    {
        $tony = Human::hydrate($this->data);

        $this->assertSame('John', $tony->name);
        $this->assertSame(0, $tony->kids);
        $this->assertSame(33.3, $tony->money);
        $this->assertSame(true, $tony->male);
        $this->assertCount(3, $tony->items);
        $this->assertSame('phone', $tony->items[0]);
        $this->assertSame('wallet', $tony->items[1]);
        $this->assertSame('keys', $tony->items[2]);
        $this->assertSame(null, $tony->car);
        $this->assertTrue($tony->mother instanceof Human);

        $mother = $tony->mother;
        $this->assertSame('Jane', $mother->name);
        $this->assertSame(2, $mother->kids);
        $this->assertSame(66.6, $mother->money);
        $this->assertSame(false, $mother->male);
        $this->assertCount(2, $mother->items);
        $this->assertSame('phone', $mother->items[0]);
        $this->assertSame('keys', $mother->items[1]);
        $this->assertTrue($mother->car instanceof Car);
        $this->assertSame(null, $mother->mother);

        $car = $mother->car;
        $this->assertSame('911', $car->type);
        $this->assertSame('Porsche', $car->brand);
    }

    public function testItReturnsNullWhenNullIsSupplied()
    {
        $tony = Hydrator::hydrateRaw(Human::class, null);

        $this->assertNull($tony);
    }

    public function testItReturnsNullWhenEmptyArrayIsSupplied()
    {
        $tony = Hydrator::hydrateRaw(Human::class, []);

        $this->assertNull($tony);
    }
}
