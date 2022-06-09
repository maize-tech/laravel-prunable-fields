<?php

namespace Maize\PrunableFields;

use Maize\PrunableFields\Commands\PrunableFieldsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PrunableFieldsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prunable-fields')
            ->hasConfigFile()
            ->hasCommand(PrunableFieldsCommand::class);
    }
}
