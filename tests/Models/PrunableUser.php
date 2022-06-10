<?php

namespace Maize\PrunableFields\Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\PrunableFields\PrunableFields;
use Maize\PrunableFields\Tests\Events\UserUpdatedEvent;

class PrunableUser extends Model
{
    use HasFactory;
    use PrunableFields;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $dispatchesEvents = [
        'updated' => UserUpdatedEvent::class,
    ];

    protected $prunable = [
        'first_name' => null,
        'last_name' => null,
    ];

    public function prunableFields(): Builder
    {
        return static::query()
            ->whereDate('created_at', '<=', now()->subDay());
    }

    protected function pruningFields(): void
    {
        logger()->warning("user {$this->getKey()} is being pruned");
    }
}
