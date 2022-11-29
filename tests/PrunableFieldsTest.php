<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Maize\PrunableFields\Events\ModelsFieldsPruned;
use Maize\PrunableFields\Support\Config;
use Maize\PrunableFields\Tests\Events\UserUpdatedEvent;
use Maize\PrunableFields\Tests\Models\PrunableUser;

test('should update prunable models', function (PrunableUser $model) {
    pruneFields([
        '--model' => PrunableUser::class,
    ]);

    assertModelHas(PrunableUser::class, [
        'id' => $model->getKey(),
        'first_name' => null,
        'last_name' => null,
        'email' => $model->email,
    ]);
})->with('user_with_prunable_fields');

test('should not update non prunable models', function (PrunableUser $model) {
    pruneFields([
        '--model' => PrunableUser::class,
    ]);

    assertModelHas(PrunableUser::class, [
        'id' => $model->getKey(),
        'first_name' => $model->first_name,
        'last_name' => $model->last_name,
        'email' => $model->email,
    ]);
})->with('user_without_prunable_fields');

test('should call the pruningFields method', function (PrunableUser $model) {
    Log::shouldReceive('warning')
        ->once()
        ->withArgs(fn ($message) => $message === "user {$model->getKey()} is being pruned");

    pruneFields([
        '--model' => PrunableUser::class,
    ]);
})->with('user_with_prunable_fields');

test('should fire model updated events', function (PrunableUser $model) {
    Event::fake();

    pruneFields([
        '--model' => PrunableUser::class,
    ]);

    Event::assertDispatched(
        UserUpdatedEvent::class,
        fn (UserUpdatedEvent $e) => $e->user->getKey() === $model->getKey()
    );
})->with('user_with_prunable_fields');

test('should fire ModelsFieldsPruned event with prunable model', function (PrunableUser $model) {
    Event::fake();

    pruneFields([
        '--model' => PrunableUser::class,
    ]);

    Event::assertDispatched(
        ModelsFieldsPruned::class,
        fn (ModelsFieldsPruned $e) => $e->count === 1 && $e->model === PrunableUser::class
    );
})->with('user_with_prunable_fields');

test('should allow the prunable models to be overridden at runtime', function (PrunableUser $model) {
    Config::resolvePrunableModelsUsing(fn () => [PrunableUser::class]);

    Event::fake();

    pruneFields([]);

    Event::assertDispatched(
        ModelsFieldsPruned::class,
        fn (ModelsFieldsPruned $e) => $e->count === 1 && $e->model === PrunableUser::class
    );

    Config::resolvePrunableModelsUsing(null);
})->with('user_with_prunable_fields');
