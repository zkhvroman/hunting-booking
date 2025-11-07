<?php

declare(strict_types=1);

namespace Src\HunterBooking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Date;
use Src\HunterBooking\Models\HuntingBooking;

final class BookingHuntingTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string|Date>>
     */
    public function rules(): array
    {
        return [
            'tour_name' => ['required', 'string', 'max:255'],
            'hunter_name' => ['required', 'string', 'max:255'],
            'guide_id' => ['required', 'uuid'],
            'tour_date' => ['required', 'date', 'after_or_equal:today', Rule::date()->format(DATE_RFC3339)],
            'participants_count' => ['required', 'integer', 'between:1,' . HuntingBooking::PARTICIPANTS_LIMIT],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tour_name' => 'Название тура',
            'hunter_name' => 'Имя охотника',
            'guide_id' => 'Гид',
            'tour_date' => 'Дата проведения',
            'participants_count' => 'Количество участников',
        ];
    }
}
