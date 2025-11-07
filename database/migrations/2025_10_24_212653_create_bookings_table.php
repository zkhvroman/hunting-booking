<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hunting_bookings', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('tour_name');
            $table->string('hunter_name');
            $table->foreignUuid('guide_id')->references('id')->on('guides')->cascadeOnDelete();
            $table->date('tour_date');
            $table->unsignedInteger('participants_count');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['guide_id', 'tour_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hunting_bookings');
    }
};
