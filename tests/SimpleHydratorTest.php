<?php

namespace AntoninMasek\SimpleHydrator\Tests;

use AntoninMasek\SimpleHydrator\Casters\Caster;
use AntoninMasek\SimpleHydrator\Exceptions\CasterException;
use AntoninMasek\SimpleHydrator\Hydrator;
use AntoninMasek\SimpleHydrator\Tests\Casters\TestingCaster;
use AntoninMasek\SimpleHydrator\Tests\Models\Car;
use AntoninMasek\SimpleHydrator\Tests\Models\ClassThatNeedsCustomCaster;
use AntoninMasek\SimpleHydrator\Tests\Models\Human;
use DateTime;
use PHPUnit\Framework\TestCase;
use TypeError;

class SimpleHydratorTest extends TestCase
{
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();

        Caster::clearCasters();

        $this->data = [
            'name'        => 'John',
            'kids'        => 0,
            'dateOfBirth' => '1969-07-20',
            'money'       => 33.3,
            'male'        => true,
            'items'       => ['phone', 'wallet', 'keys'],
            'car'         => null,
            'mother'      => [
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
        $person = Hydrator::hydrate(Human::class, $this->data);

        $this->assertSame('John', $person->name);
        $this->assertSame(0, $person->kids);
        $this->assertTrue($person->dateOfBirth instanceof DateTime);
        $this->assertSame(33.3, $person->money);
        $this->assertSame(true, $person->male);
        $this->assertCount(3, $person->items);
        $this->assertCount(3, $person->items);
        $this->assertSame('phone', $person->items[0]);
        $this->assertSame('wallet', $person->items[1]);
        $this->assertSame('keys', $person->items[2]);
        $this->assertSame(null, $person->car);
        $this->assertTrue($person->mother instanceof Human);

        $mother = $person->mother;
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

        $car = Hydrator::hydrate(Car::class, $this->data['mother']['car']);
        $this->assertSame('911', $car->type);
        $this->assertSame('Porsche', $car->brand);
    }

    public function testObjectCanHydrateItselfWhenExtendingHydrator()
    {
        $person = Human::fromArray($this->data);

        $this->assertSame('John', $person->name);
        $this->assertSame(0, $person->kids);
        $this->assertSame(33.3, $person->money);
        $this->assertSame(true, $person->male);
        $this->assertCount(3, $person->items);
        $this->assertSame('phone', $person->items[0]);
        $this->assertSame('wallet', $person->items[1]);
        $this->assertSame('keys', $person->items[2]);
        $this->assertSame(null, $person->car);
        $this->assertTrue($person->mother instanceof Human);

        $mother = $person->mother;
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
        $person = Hydrator::hydrate(Human::class, null);

        $this->assertNull($person);
    }

    public function testItReturnsNullWhenEmptyArrayIsSupplied()
    {
        $person = Hydrator::hydrate(Human::class, []);

        $this->assertNull($person);
    }

    public function testObjectCanSetValues()
    {
        $person = Human::fromArray($this->data)->firstName('John');

        $this->assertSame('John', $person->first_name);
    }

    public function testObjectTypeError()
    {
        $this->expectException(TypeError::class);

        $person = Human::make()->dateOfBirth('test');
    }

    public function testItFailsWithoutCustomCaster()
    {
        $data = ['brand' => 'Ford', 'type' => 'Mustang', 'customCaster' => 36.0];

        $this->expectException(CasterException::class);

        Hydrator::hydrate(Car::class, $data);
    }

    public function testItIsPossibleToWriteCaster()
    {
        $data = ['brand' => 'Ford', 'type' => 'Mustang', 'customCaster' => 36.0];

        Caster::registerCaster(ClassThatNeedsCustomCaster::class, TestingCaster::class);

        $class = Hydrator::hydrate(Car::class, $data);

        $expectedValue = floatval((new DateTime())->format('n')) + 36;
        $this->assertSame($expectedValue, $class->customCaster->value);
    }

    public function testItIsPossibleToWriteAnonymousCaster()
    {
        $data = ['brand' => 'Ford', 'type' => 'Mustang', 'customCaster' => 36.0];

        Caster::registerCaster(ClassThatNeedsCustomCaster::class, function ($value) {
            $class = new ClassThatNeedsCustomCaster();

            $class->value = floatval((new DateTime())->format('n')) + $value;

            return $class;
        });

        $class = Hydrator::hydrate(Car::class, $data);

        $expectedValue = floatval((new DateTime())->format('n')) + 36;
        $this->assertSame($expectedValue, $class->customCaster->value);
    }

    public function testItIsPossibleToOverwriteDefaultCaster()
    {
        $data                = $this->data;
        $data['dateOfBirth'] = -14256000;

        $person = Human::fromArray($data);
        $this->assertNotEquals(1969, $person->dateOfBirth->format('Y'));
        $this->assertNotEquals(07, $person->dateOfBirth->format('m'));
        $this->assertNotEquals(20, $person->dateOfBirth->format('d'));

        Caster::registerCaster(DateTime::class, function ($value) {
            return (new DateTime())->setTimestamp($value);
        });

        $person = Human::fromArray($data);

        $this->assertTrue($person->dateOfBirth instanceof DateTime);
        $this->assertEquals(1969, $person->dateOfBirth->format('Y'));
        $this->assertEquals(07, $person->dateOfBirth->format('m'));
        $this->assertEquals(20, $person->dateOfBirth->format('d'));
    }
}
