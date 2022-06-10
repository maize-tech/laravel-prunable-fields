<?php

namespace Maize\PrunableFields\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Maize\PrunableFields\Events\ModelsFieldsPruned;
use Maize\PrunableFields\MassPrunableFields;
use Maize\PrunableFields\PrunableFields;
use Maize\PrunableFields\Support\Config;

class PruneFieldsCommand extends Command
{
    protected $signature = 'model:prune-fields
                                {--model=* : Class names of the models to be pruned}
                                {--except=* : Class names of the models to be excluded from pruning}
                                {--chunk=1000 : The number of models to retrieve per chunk of models to be pruned}
                                {--pretend : Display the number of prunable records found instead of pruning them}';

    public $description = 'Prune model fields that are no longer needed';

    public function handle(): void
    {
        $models = $this->models();

        if ($models->isEmpty()) {
            $this->info('No prunable models found.');

            return;
        }

        if ($this->option('pretend')) {
            $models->each(fn ($model) => $this->pretendToPrune($model));

            return;
        }

        Event::listen(ModelsFieldsPruned::class, function ($event) {
            $this->info("{$event->count} [{$event->model}] records have been pruned.");
        });

        $models->each(function ($model) {
            $instance = new $model();

            $chunkSize = property_exists($instance, 'prunableFieldsChunkSize')
                ? $instance->prunableFieldsChunkSize
                : $this->option('chunk');

            $total = $this->isPrunable($model)
                ? $instance->pruneAllFields($chunkSize)
                : 0;

            if ($total === 0) {
                $this->info("No prunable [$model] records found.");
            }
        });

        Event::forget(ModelsFieldsPruned::class);
    }

    protected function models(): Collection
    {
        if (! empty($models = $this->option('model'))) {
            return collect($models)
                ->filter(fn ($model) => class_exists($model))
                ->values();
        }

        $except = $this->option('except');

        return collect(Config::getPrunableModels())
            ->reject(fn ($model) => in_array($model, $except))
            ->filter(fn ($model) => class_exists($model))
            ->filter(fn ($model) => $this->isPrunable($model))
            ->values();
    }

    protected function isPrunable(string $model): bool
    {
        $uses = class_uses_recursive($model);

        return in_array(PrunableFields::class, $uses) || in_array(MassPrunableFields::class, $uses);
    }

    protected function pretendToPrune(string $model): void
    {
        $instance = new $model();

        $count = $instance
            ->prunableFields()
            ->when(
                in_array(SoftDeletes::class, class_uses_recursive(get_class($instance))),
                fn ($query) => $query->withTrashed()
            )
            ->count();

        if ($count === 0) {
            $this->info("No prunable [$model] records found.");
        } else {
            $this->info("{$count} [{$model}] records will be pruned.");
        }
    }
}
