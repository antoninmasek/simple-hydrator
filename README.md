# Simple Hydrator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antoninmasek/simple-hydrator.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/simple-hydrator)
[![Tests](https://github.com/antoninmasek/simple-hydrator/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/antoninmasek/simple-hydrator/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/antoninmasek/simple-hydrator.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/simple-hydrator)

This package aims to be an extremely easy-to-use object hydrator. You give it an array and it will try to hydrate your
data object. I found myself repeating this logic a few times on different projects, so I decided to package it up. I am
well aware, that there are far more superior alternatives, but my aim was to do the implementation as simple as
possible.

Also, the main objective for me was, to scratch my own itch. So even though the implementation is now limited and
possibly contains some issues, for me, it is currently doing the job just fine. If you, however, find yourself using it
too and find an issue, feel free to PR it :)

## Installation

You can install the package via composer:

```bash
composer require antoninmasek/simple-hydrator
```

## Usage

To hydrate your data object you have two options:

### Hydrator::hydrate

You can use the `Hydrator` as follows:

```php
$human = Hydrator::hydrate(Human::class, $data);
```

Advantage of this approach is, that your data object does not have to extend anything. Downside is, that without PHP Doc
you won't have autocompletion.

### _YourDataObject_::fromArray

This way, you can extend your data object with `DataObject` abstract class, which will enable you to call `fromArray`
method directly on your data object.

```php
$human = Human::fromArray($data);
```

Main advantage is autocompletion as well as better readability. Disadvantage is, that you have to extend your data
object. At least the parent. Nested object does not have to extend anything.

### Collections
If you find yourself in a scenario, where you'd have let's say a `Car` object that has many `Key` objects:
```php
$car = [
    'brand' => 'Chevrolet',
    'type'  => 'Camaro',
    'keys'  => [
        [
            'name'      => 'main',
            'is_active' => true,
        ],
        [
            'name'      => 'secondary',
            'is_active' => false,
        ],
    ],
];
```
And you need to cast each of the `keys` to a `Key` object, you may add `#[Collection(Key::class)]` attribute to your data object definition as such:
```php
class Car
{
    public string $type;
    public string $brand;
    public ?ClassThatNeedsCustomCaster $customCaster;

    #[Collection(Key::class)]
    public ?array $keys;
}
```
This ensures correct casting and you will end up with an array of `Key` objects.

### DTO Making

For each of your DTO's properties you can use either a camelCase or snake_case approach to set their values which ever
suites your preference, in the example below we have the propeties `first_name` and `last_name` set on the DTO here.

```php
$person = Human::make()
    ->firstName('John')
    ->lastName('Doe')
    ->kids(3);
```

### Casters

For cases, where the type of property isn't built in PHP, or it needs a special care than just try to fill properties
by name it is possible to write a caster. 

#### Caster class

The first way to define a caster is to create a class, that extends `AntoninMasek\SimpleHydrator\Casters\Caster`. You only need to implement the `cast` method which is supplied
with `$value` parameter that contains the raw data from the input array, that should be used to hydrate this class.

As an example take a look at simple `DateTime` caster:

```php
class DateTimeCaster extends Caster
{
    public function cast(mixed $value): ?DateTime
    {
        if (is_null($value)) {
            return null;
        }

        return new DateTime($value);
    }
}
```

It expects the `$value` to be a string in valid date format. For example `1969-07-20` and returns a `DateTime` object with this date.

#### Anonymous caster
If you don't want to create a caster class you can create anonymous caster by supplying a closure instead of a caster class.

```php
Caster::registerCaster(DateTime::class, function ($value) {
    if (is_null($value)) {
        return null;
    }

    return new DateTime($value);
});
```

#### Registering casters
You can register casters in two ways. First is to specify the mapping between all classes and their respective casters:
```php
Caster::setCasters([
    YourObject::class => YourObjectCaster::class,
    YourSecondObject::class => AnotherCaster::class,
]);
```

Or just specify one caster at a time:
```php
Caster::registerCaster(YourObject::class, YourObjectCaster::class);
```

To clear all caster you may use:
```php
Caster::clearCasters();
```

#### Overwriting default casters
If any of the default casters in the package does not suit your needs you can easily overwrite it. All you need to do is register your caster for the specific class.
Registered casters have higher priority and default casters in the package are used if no mapping for the specific class is supplied.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Antonín Mašek](https://github.com/antoninmasek)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
