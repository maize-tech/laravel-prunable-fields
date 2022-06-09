<?php

namespace Maize\PrunableFields\Commands;

use Illuminate\Console\Command;

class PrunableFieldsCommand extends Command
{
    public $signature = 'laravel-prunable-fields';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
