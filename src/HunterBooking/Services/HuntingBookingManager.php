<?php

declare(strict_types=1);

namespace Src\HunterBooking\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Src\HunterBooking\Models\Guide;
use Src\HunterBooking\Models\HuntingBooking;
use Src\HunterBooking\Services\Errors\GuideHasAlreadyBookedForDateError;
use Src\HunterBooking\Services\Errors\GuideIsNotActiveError;
use Src\HunterBooking\Services\Errors\GuideNotFoundError;

final readonly class HuntingBookingManager
{
    /**
     * @throws GuideNotFoundError
     * @throws GuideIsNotActiveError
     * @throws GuideHasAlreadyBookedForDateError
     */
    public function book(BookHuntingTour $huntingTour): void
    {
        DB::transaction(function () use ($huntingTour): void {
            /** @var Guide $guide */
            $guide = Guide::query()->findOr(
                $huntingTour->guideId,
                fn(): never => throw new GuideNotFoundError($huntingTour->guideId),
            );

            if (! $guide->is_active) {
                throw new GuideIsNotActiveError($guide);
            }

            $booking = new HuntingBooking([
                'tour_name' => $huntingTour->tourName,
                'hunter_name' => $huntingTour->hunterName,
                'guide_id' => $guide->id,
                'tour_date' => $huntingTour->tourDate,
                'participants_count' => $huntingTour->participantsCount,
            ]);

            try {
                $booking->save();
            } catch (UniqueConstraintViolationException) {
                throw new GuideHasAlreadyBookedForDateError($guide, $huntingTour->tourDate);
            }
        });
    }
}
