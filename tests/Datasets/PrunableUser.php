<?php

use Maize\PrunableFields\Tests\Models\PrunableUser;

dataset('user_with_prunable_fields', function () {
    yield fn () => PrunableUser::factory()->create([
        'created_at' => now()->subMonth(),
    ]);
});

dataset('user_without_prunable_fields', function () {
    yield fn () => PrunableUser::factory()->create();
});
