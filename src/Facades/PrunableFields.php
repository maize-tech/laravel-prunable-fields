<?php

namespace Maize\PrunableFields\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Maize\PrunableFields\PrunableFields
 */
class PrunableFields extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-prunable-fields';
    }
}
