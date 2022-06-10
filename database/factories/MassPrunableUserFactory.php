<?php

namespace Maize\PrunableFields\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maize\PrunableFields\Tests\Models\MassPrunableUser;

class MassPrunableUserFactory extends Factory
{
    protected $model = MassPrunableUser::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
        ];
    }
}
