<?php

declare(strict_types=1);

namespace Database\Factories\HunterBooking;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\HunterBooking\Models\HuntingBooking;

/**
 * @extends Factory<HuntingBooking>
 */
final class HunterBookingFactory extends Factory
{
    /**
     * @var class-string<HuntingBooking>
     */
    protected $model = HuntingBooking::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tour_name' => $this->faker->unique()->company(),
            'hunter_name' => $this->faker->unique()->name('male'),
            'tour_date' => $this->faker->dateTimeBetween(startDate: 'now', endDate: '+1 month'),
            'guide_id' => GuideFactory::new()->create(),
            'participants_count' => $this->faker->numberBetween(1, 15),
        ];
    }
}
