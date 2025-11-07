<?php

declare(strict_types=1);

namespace Tests\Booking;

use Database\Factories\HunterBooking\GuideFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group('api')]
final class GuidesTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetAllActiveGuides(): void
    {
        GuideFactory::new()->count($activeTotal = 7)->create();
        GuideFactory::new()->notActive()->create();

        $response = $this->getJson('/api/guides');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                static fn(AssertableJson $json) => $json->has($activeTotal)
                    ->each(
                        static fn(AssertableJson $guide) => $guide->hasAll(['id', 'name', 'experience_years', 'updated_at'])
                            ->whereAllType([
                                'id' => 'string',
                                'name' => 'string',
                                'experience_years' => 'integer',
                                'updated_at' => 'string',
                            ]),
                    ),
            );
    }

    public function testCanFilterGuidesByMinExperience(): void
    {
        foreach ([1, 1, 2, 5, 10, 8, 3] as $year) {
            GuideFactory::new()->withExperience($year)->create();
        }

        $response = $this->getJson(uri: '/api/guides?min_experience=3');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                static fn(AssertableJson $json) => $json->has(4)
                    ->each(
                        static fn(AssertableJson $guide) => $guide->hasAll(['id', 'name', 'experience_years', 'updated_at'])
                            ->whereAllType([
                                'id' => 'string',
                                'name' => 'string',
                                'experience_years' => 'integer',
                                'updated_at' => 'string',
                            ]),
                    ),
            );
    }
}
