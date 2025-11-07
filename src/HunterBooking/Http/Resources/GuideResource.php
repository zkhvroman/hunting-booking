<?php

declare(strict_types=1);

namespace Src\HunterBooking\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $name,
 * @property non-negative-int $experience_years
 * @property Carbon $updated_at
 */
final class GuideResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'experience_years' => $this->experience_years,
            'updated_at' => $this->updated_at->toRfc3339String(),
        ];
    }
}
