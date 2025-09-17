<?php

use App\Enums\SessionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->integer('capacity');
            $table->integer('min_attendees');
            $table->enum('status', array_column(SessionStatus::cases(), 'value'))->default(SessionStatus::SCHEDULED->value);
            $table->foreignId('generated_from_schedule_id')->nullable()->constrained('class_schedules')->onDelete('set null');
            $table->timestamps();

            $table->index('start_at');
            $table->index(['instructor_id', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
