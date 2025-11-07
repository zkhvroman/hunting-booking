<?php

declare(strict_types=1);

namespace Src\ItOps\EloquentUuid;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Src\ItOps\Uuid\Uuid;

trait HasUuids
{
    use HasUniqueStringIds;

    public function newUniqueId(): string
    {
        return (string) Uuid::v7();
    }

    protected function isValidUniqueId(mixed $value): bool
    {
        return \is_string($value) && Uuid::isUuid($value);
    }
}
