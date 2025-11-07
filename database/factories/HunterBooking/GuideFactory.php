<?php

declare(strict_types=1);

namespace Database\Factories\HunterBooking;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\HunterBooking\Models\Guide;
use Src\ItOps\Uuid\Uuid;

/**
 * @extends Factory<Guide>
 */
final class GuideFactory extends Factory
{
    /**
     * @var class-string<Guide>
     */
    protected $model = Guide::class;

    public function definition(): array
    {
        return [
            'id' => Uuid::v7(),
            'name' => fake()->unique()->name('male'),
            'experience_years' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    /**
     * @return Factory<Guide>
     */
    public function notActive(): Factory
    {
        return $this->state(fn(): array => ['is_active' => false]);
    }

    /**
     * @return Factory<Guide>
     */
    public function withExperience(int $years = 3): Factory
    {
        return $this->state(fn(): array => ['experience_years' => $years]);
    }
}
