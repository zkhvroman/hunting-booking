<?php

declare(strict_types=1);

namespace Src\HunterBooking\Models;

use Carbon\Carbon;
use Carbon\Traits\Timestamp;
use Database\Factories\HunterBooking\GuideFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\HunterBooking\Http\Resources\GuideResource;

/**
 * @property string $id
 * @property string $name
 * @property non-negative-int $experience_years
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[UseFactory(GuideFactory::class)]
#[UseResourceCollection(GuideResource::class)]
final class Guide extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Timestamp;

    /**
     * @return HasMany<HuntingBooking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(HuntingBooking::class);
    }

    /**
     * @param Builder<Guide> $builder
     */
    #[Scope]
    protected function active(Builder $builder): void
    {
        $builder->where('is_active', true);
    }
}
