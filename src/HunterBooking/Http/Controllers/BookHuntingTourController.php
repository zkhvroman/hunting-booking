<?php

declare(strict_types=1);

namespace Src\HunterBooking\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Src\HunterBooking\Http\Requests\BookingHuntingTourRequest;
use Src\HunterBooking\Services\BookHuntingTour;
use Src\HunterBooking\Services\Errors\GuideHasAlreadyBookedForDateError;
use Src\HunterBooking\Services\Errors\GuideIsNotActiveError;
use Src\HunterBooking\Services\Errors\GuideNotFoundError;
use Src\HunterBooking\Services\HuntingBookingManager;
use Src\ItOps\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final class BookHuntingTourController
{
    public function __invoke(BookingHuntingTourRequest $request, HuntingBookingManager $manager): JsonResponse
    {
        /** @var Carbon $date */
        $date = $request->date('tour_date', DATE_RFC3339);
        $bookingId = Uuid::v7();

        try {
            $manager->book(new BookHuntingTour(
                bookingId: $bookingId,
                tourName: (string) $request->string('tour_name'),
                hunterName: (string) $request->string('hunter_name'),
                guideId: Uuid::fromString((string) $request->string('guide_id')),
                tourDate: $date,
                participantsCount: $request->integer('participants_count'),
            ));

            return response()->json(data: ['id' => $bookingId], status: Response::HTTP_CREATED);
        } catch (GuideNotFoundError $error) {
            return response()->json(
                data: ['message' => "Гид с id '{$error->guideId}' не существует."],
                status: Response::HTTP_BAD_REQUEST,
            );
        } catch (GuideIsNotActiveError $error) {
            return response()->json(
                data: ['message' => "Гид '{$error->guide->name}' недоступен для выбора."],
                status: Response::HTTP_BAD_REQUEST,
            );
        } catch (GuideHasAlreadyBookedForDateError $error) {
            return response()->json(
                data: ['message' => "Гид '{$error->guide->name}' уже занят на выбранную дату."],
                status: Response::HTTP_BAD_REQUEST,
            );
        }
    }
}
