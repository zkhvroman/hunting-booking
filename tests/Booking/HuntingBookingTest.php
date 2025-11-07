<?php

declare(strict_types=1);

namespace Tests\Booking;

use Database\Factories\HunterBooking\GuideFactory;
use Database\Factories\HunterBooking\HunterBookingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\Group;
use Src\HunterBooking\Models\Guide;
use Src\ItOps\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

#[Group('api')]
final class HuntingBookingTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesBookingSuccessfully(): void
    {
        /** @var Guide $guide */
        $guide = GuideFactory::new()->create();

        $payload = [
            'tour_name' => 'Охота в горах',
            'hunter_name' => 'Егор Сергеев',
            'guide_id' => $guide->id,
            'tour_date' => now()->addDay()->toRfc3339String(),
            'participants_count' => 3,
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id']);

        $this->assertDatabaseHas('hunting_bookings', [
            'tour_name' => 'Охота в горах',
            'guide_id' => $guide->id,
        ]);
    }

    public function testReturnsErrorIfGuideIdDoesNotExist(): void
    {
        $nonExistentGuideId = Uuid::v7();

        $payload = [
            'tour_name' => 'Лесная охота',
            'hunter_name' => 'Сергей Иванов',
            'guide_id' => $nonExistentGuideId,
            'tour_date' => now()->addDay()->toRfc3339String(),
            'participants_count' => 4,
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => "Гид с id '{$nonExistentGuideId}' не существует.",
            ]);

        $this->assertDatabaseEmpty('hunting_bookings');
    }

    public function testReturnsErrorIfGuideIsNotActive(): void
    {
        /** @var Guide $guide */
        $guide = GuideFactory::new()->notActive()->create();

        $payload = [
            'tour_name' => 'Лесная охота',
            'hunter_name' => 'Сергей Иванов',
            'guide_id' => $guide->id,
            'tour_date' => now()->addDay()->toRfc3339String(),
            'participants_count' => 4,
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => "Гид '{$guide->name}' недоступен для выбора.",
            ]);

        $this->assertDatabaseEmpty('hunting_bookings');
    }

    public function testReturnsErrorIfGuideAlreadyBookedForTheDate(): void
    {
        /** @var Guide $guide */
        $guide = GuideFactory::new()->create();

        HunterBookingFactory::new()
            ->for($guide)
            ->create(['tour_date' => $tourDate = now()->addDay()]);

        $payload = [
            'tour_name' => 'Охота на лося',
            'hunter_name' => 'Иван Петров',
            'guide_id' => $guide->id,
            'tour_date' => $tourDate->toRfc3339String(),
            'participants_count' => 5,
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => "Гид '{$guide->name}' уже занят на выбранную дату.",
            ]);

        $this->assertDatabaseCount('hunting_bookings', 1);
    }
}
