<?php

use App\Enums\ReservationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('class_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', array_column(ReservationStatus::cases(), 'value'))->default(ReservationStatus::BOOKED->value);
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('cancellation_deadline');
            $table->timestamps();

            $table->unique(['session_id', 'user_id'], 'uq_reservations_session_user');
            $table->index(['user_id', 'status']);
            $table->index(['session_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
