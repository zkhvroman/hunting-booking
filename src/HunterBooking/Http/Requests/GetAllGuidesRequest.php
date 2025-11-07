<?php

declare(strict_types=1);

namespace Src\HunterBooking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class GetAllGuidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'min_experience' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
