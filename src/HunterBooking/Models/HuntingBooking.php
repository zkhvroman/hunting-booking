<?php

declare(strict_types=1);

namespace Src\HunterBooking\Models;

use Carbon\Carbon;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\ItOps\EloquentUuid\HasUuids;

/**
 * @property string $id
 * @property string $tour_name
 * @property string $hunter_name
 * @property string $guide_id
 * @property Carbon $date
 * @property string $participants_count
 */
final class HuntingBooking extends Model
{
    use HasUuids;
    use Timestamp;
    public const int PARTICIPANTS_LIMIT = 10;

    protected $fillable = [
        'tour_name',
        'hunter_name',
        'guide_id',
        'tour_date',
        'participants_count',
    ];

    protected $casts = ['tour_date' => 'date'];

    /**
     * @return BelongsTo<Guide, $this>
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}
