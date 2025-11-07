<?php

declare(strict_types=1);

namespace Src\HunterBooking\Services\Errors;

use Src\ItOps\Uuid\Uuid;

final class GuideNotFoundError extends \RuntimeException
{
    public function __construct(public readonly Uuid $guideId)
    {
        parent::__construct();
    }
}
