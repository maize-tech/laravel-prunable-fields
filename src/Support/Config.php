<?php

namespace Maize\PrunableFields\Support;

class Config
{
    public static function getPrunableModels(): array
    {
        return config('prunable-fields.models', []);
    }
}
