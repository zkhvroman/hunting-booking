<?php

declare(strict_types=1);

namespace Src\HunterBooking\Services\Errors;

use Carbon\Carbon;
use Src\HunterBooking\Models\Guide;

final class GuideHasAlreadyBookedForDateError extends \RuntimeException
{
    public function __construct(
        public readonly Guide $guide,
        public readonly Carbon $date,
    ) {
        parent::__construct();
    }
}
