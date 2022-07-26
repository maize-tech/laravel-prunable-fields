<?php

namespace Maize\PrunableFields\Support;

class Config
{
    protected static \Closure $callback;

    public static function resolvePrunableModelsUsing(\Closure $callback)
    {
        static::$callback = $callback;
    }

    public static function getPrunableModels(): array
    {
        if(isset(static::$callback)) {
            return call_user_func(static::$callback);
        }
        return config('prunable-fields.models', []);
    }
}
