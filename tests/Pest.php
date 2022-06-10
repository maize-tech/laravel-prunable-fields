<?php

use Maize\PrunableFields\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function pruneFields(array $arguments)
{
    return test()
        ->artisan('model:prune-fields', $arguments);
}

function assertModelHas(string $model, array $data)
{
    return test()->assertDatabaseHas(
        app($model)->getTable(),
        $data
    );
}
