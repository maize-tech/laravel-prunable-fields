
# Laravel Prunable Fields

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-prunable-fields.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-prunable-fields)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-prunable-fields/run-tests?label=tests)](https://github.com/maize-tech/laravel-prunable-fields/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-prunable-fields/Check%20&%20fix%20styling?label=code%20style)](https://github.com/maize-tech/laravel-prunable-fields/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-prunable-fields.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-prunable-fields)

This package allows you to clean model fields with an easy command.
The feature is highly inspired by Laravel's Prunable core feature, and allows you to easily adapt all your existing models.

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
    /*
    |--------------------------------------------------------------------------
    | Prunable models
    |--------------------------------------------------------------------------
    |
    | Here you may specify the list of fully qualified class names of prunable
    | models.
    | All models listed here will be pruned when executing the model:prune-fields
    | command without passing the --model option.
    |
    */

    'models' => [
        // \App\Models\User::class,
    ],

];
```

## Usage

### Prunable models

To use the package, simply add the `Maize\PrunableFields\PrunableFields` trait to all models you want to clean.

Once done, you can define the list of attributes who should be cleaned up by implementing the `$prunable` class property.
The array key should be the attribute name, whereas the array value should be the value you want the attribute to be updated to.

After that, implement the `prunableFields` method which should return an Eloquent query builder that resolves the models that should be cleaned up.

If needed, you can also override the `pruningFields` and `prunedFields` methods (which are empty by default) to execute some actions before and after the model is being updated.

Here's an example model including the `PrunableFields` trait:

``` php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\PrunableFields\PrunableFields;

class User extends Model
{
    use PrunableFields;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $prunable = [
        'first_name' => null,
        'last_name' => null,
    ];
    
    public function prunableFields(): Builder
    {
        return static::query()
            ->whereDate('created_at', '<=', now()->subDay());
    }
    
    protected function pruningFields(): void
    {
        logger()->warning("User {$this->getKey()} is being pruned");
    }

    protected function prunedFields(): void
    {
        logger()->warning("User {$this->getKey()} has been pruned");
    }
}
```

All you have to do now is including the model's class name in `models` attribute under `config/prunable-fields.php`:

``` php
'models' => [
    \App\Models\User::class,
],
```

That's it! From now on, the `model:prune-fields` command will do all the magic.

In our example, all users created before the current day will be updated with a null value for both `first_name` and `last_name` attributes.

### Mass prunable models

When using the `MassPrunableFields` trait all models will be updated with a raw database query.

In this case, `pruningFields` and `prunedFields` methods will not be invoked, and models will not fire the `updating` or `updated` events.

This way there is no need to retrieve all models before updating them, making the command execution way faster when working with a large number of entries.

``` php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\PrunableFields\MassPrunableFields;

class User extends Model
{
    use MassPrunableFields;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $prunable = [
        'first_name' => null,
        'last_name' => null,
    ];
    
    public function prunableFields(): Builder
    {
        return static::query()
            ->whereDate('created_at', '<=', now()->subDay());
    }
}
```

### Scheduling models cleanup

The package is pretty useful when you automatize the execution of the `model:prune-fields` command, using Laravel's scheduling.
All you have to do is add the following instruction to the `schedule` method of the console kernel (usually located under the `App\Console` directory):

``` php
$schedule->command('model:prune-fields')->daily();
```

By default, when executing the `model:prune-fields` command the package will take all prunable models specified in `models` attribute under `config/prunable-fields.php`.

If you want to restrict the model list you want to automatically clean up, you can pass the `--model` option to the command:

``` php
$schedule->command('model:prune-fields', [
    '--model' => [User::class],
])->daily();
```

Alternatively, you can clean up all models listed in your config and exclude some of them with the `--execpt` command option:

``` php
$schedule->command('model:prune-fields', [
    '--except' => [PleaseLetMeCleanThisModelByHandsThankYou::class],
])->daily();
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
