<?php

namespace Maize\PrunableFields\Tests\Events;

use Illuminate\Database\Eloquent\Model;

class UserUpdatedEvent
{
    public function __construct(
        public Model $user
    ) {
    }
}
