
# Laravel Prunable Fields

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-prunable-fields.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-prunable-fields)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-prunable-fields/run-tests?label=tests)](https://github.com/maize-tech/laravel-prunable-fields/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-prunable-fields/Check%20&%20fix%20styling?label=code%20style)](https://github.com/maize-tech/laravel-prunable-fields/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-prunable-fields.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-prunable-fields)

This package aims to reproduce the Prunable core feature of Laravel to clean column values instead of entire rows.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-prunable-fields
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="prunable-fields-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$prunableFields = new Maize\PrunableFields();
echo $prunableFields->echoPhrase('Hello, Maize!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Riccardo Dalla Via](https://github.com/riccardodallavia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
