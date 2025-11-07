<?php

declare(strict_types=1);

namespace Database\Seeders\HunterBooking;

use Database\Factories\HunterBooking\GuideFactory;
use Illuminate\Database\Seeder;

final class GuideSeeder extends Seeder
{
    public function run(): void
    {
        GuideFactory::new()->count(10)->create();
        GuideFactory::new()->notActive()->count(5)->create();
    }
}
