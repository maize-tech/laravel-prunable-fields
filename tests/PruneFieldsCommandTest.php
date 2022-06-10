<?php

use Maize\PrunableFields\Tests\Models\PrunableUser;

test('should print the amount of pruned records', function (PrunableUser $model) {
    pruneFields([
        '--model' => PrunableUser::class,
    ])->expectsOutput("1 [{$model->getMorphClass()}] records have been pruned.");
})->with('user_with_prunable_fields');

test('should print no records have been pruned', function (PrunableUser $model) {
    pruneFields([
        '--model' => PrunableUser::class,
    ])->expectsOutput("No prunable [{$model->getMorphClass()}] records found.");
})->with('user_without_prunable_fields');

test('should print the amount of prunable records', function (PrunableUser $model) {
    pruneFields([
        '--model' => PrunableUser::class,
        '--pretend' => true,
    ])->expectsOutput("1 [{$model->getMorphClass()}] records will be pruned.");
})->with('user_with_prunable_fields');

test('should print no models found with empty list', function () {
    pruneFields([
        //
    ])->expectsOutput("No prunable models found.");
});
