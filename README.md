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

### DTO Making

For each of your DTO's properties you can use either a camelCase or snake_case approach to set their values which ever
suites your preference, in the example below we have the propeties `first_name` and `last_name` set on the DTO here.


```php
$person = Human::make()
    ->firstName('John')
    ->lastName('Doe')
    ->kids(3);
```

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
