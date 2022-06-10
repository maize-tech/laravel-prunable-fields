<?php

namespace Maize\PrunableFields\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ModelsFieldsPruned
{
    use Dispatchable;

    public function __construct(
        public string $model,
        public int $count
    ) {
    }
}
