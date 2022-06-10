<?php

use Maize\PrunableFields\Tests\Models\MassPrunableUser;

dataset('user_with_mass_prunable_fields', function () {
    yield fn () => MassPrunableUser::factory()->create([
        'created_at' => now()->subMonth(),
    ]);
});

dataset('user_without_mass_prunable_fields', function () {
    yield fn () => MassPrunableUser::factory()->create();
});
