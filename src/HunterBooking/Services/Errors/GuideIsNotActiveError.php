<?php

declare(strict_types=1);

namespace Src\HunterBooking\Services\Errors;

use Src\HunterBooking\Models\Guide;

final class GuideIsNotActiveError extends \RuntimeException
{
    public function __construct(public readonly Guide $guide)
    {
        parent::__construct();
    }
}
