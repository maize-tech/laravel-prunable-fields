<?php

namespace Maize\PrunableFields\Support;

class Config
{
    /**
     * @var callable|null
     */
    protected static $callback;

    public static function resolvePrunableModelsUsing(?callable $callback): void
    {
        static::$callback = $callback;
    }

    public static function getPrunableModels(): array
    {
        if (is_callable(static::$callback)) {
            return call_user_func(static::$callback);
        }

        return config('prunable-fields.models', []);
    }
}
