<?php

use Illuminate\Support\Facades\Event;
use Maize\PrunableFields\Events\ModelsFieldsPruned;
use Maize\PrunableFields\Tests\Events\UserUpdatedEvent;
use Maize\PrunableFields\Tests\Models\MassPrunableUser;

test('should update mass prunable models', function (MassPrunableUser $model) {
    pruneFields([
        '--model' => MassPrunableUser::class,
    ]);

    assertModelHas(MassPrunableUser::class, [
        'id' => $model->getKey(),
        'first_name' => null,
        'last_name' => null,
        'email' => $model->email,
    ]);
})->with('users_with_mass_prunable_fields');

test('should not update non prunable models', function (MassPrunableUser $model) {
    pruneFields([
        '--model' => MassPrunableUser::class,
    ]);

    assertModelHas(MassPrunableUser::class, [
        'id' => $model->getKey(),
        'first_name' => $model->first_name,
        'last_name' => $model->last_name,
        'email' => $model->email,
    ]);
})->with('user_without_mass_prunable_fields');

test('should not fire model updated events', function (MassPrunableUser $model) {
    Event::fake();

    pruneFields([
        '--model' => MassPrunableUser::class,
    ]);

    Event::assertNotDispatched(
        UserUpdatedEvent::class,
        fn (UserUpdatedEvent $e) => $e->user->getKey() === $model->getKey()
    );
})->with('user_with_mass_prunable_fields');

test('should fire ModelsFieldsPruned event with mass prunable model', function (MassPrunableUser $model) {
    Event::fake();

    pruneFields([
        '--model' => MassPrunableUser::class,
    ]);

    Event::assertDispatched(
        ModelsFieldsPruned::class,
        fn (ModelsFieldsPruned $e) => $e->count === 1 && $e->model === MassPrunableUser::class
    );
})->with('user_with_mass_prunable_fields');
