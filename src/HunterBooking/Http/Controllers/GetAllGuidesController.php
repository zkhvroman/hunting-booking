<?php

declare(strict_types=1);

namespace Src\HunterBooking\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Src\HunterBooking\Http\Requests\GetAllGuidesRequest;
use Src\HunterBooking\Http\Resources\GuideResource;
use Src\HunterBooking\Models\Guide;

final class GetAllGuidesController
{
    public function __invoke(GetAllGuidesRequest $request): JsonResponse
    {
        /** @var Collection<int, Guide> $guides */
        $guides = Guide::query()
            ->select('*')
            ->active()
            ->when($request->has('min_experience'), static function (Builder $builder) use ($request): void {
                $builder->where('experience_years', '>=', $request->integer('min_experience'));
            })
            ->orderBy('name')
            ->get();

        return response()->json(GuideResource::collection($guides));
    }
}
