<?php

namespace Maize\PrunableFields\Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\PrunableFields\MassPrunableFields;
use Maize\PrunableFields\Tests\Events\UserUpdatedEvent;

class MassPrunableUser extends Model
{
    use HasFactory;
    use MassPrunableFields;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $dispatchesEvents = [
        'updated' => UserUpdatedEvent::class
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
}
