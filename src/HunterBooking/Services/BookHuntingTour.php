<?php

declare(strict_types=1);

namespace Src\HunterBooking\Services;

use Carbon\Carbon;
use Src\ItOps\Uuid\Uuid;

final readonly class BookHuntingTour
{
    public function __construct(
        public Uuid $bookingId,
        public string $tourName,
        public string $hunterName,
        public Uuid $guideId,
        public Carbon $tourDate,
        public int $participantsCount,
    ) {}
}
