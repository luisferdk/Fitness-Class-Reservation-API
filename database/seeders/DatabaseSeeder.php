<?php

namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Models\ClassSchedule;
use App\Models\ClassSession;
use App\Models\ClassType;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createUsers();
        /* $this->createClassTypes();
        $this->createClassSchedules();
        $this->createClassSessions();
        $this->createReservations(); */
    }

    /**
     * Create users with different roles for testing
     */
    private function createUsers(): void
    {
        // Create admin users
        User::factory()->admin()->active()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123#')
        ]);

        // Create instructor users
        User::factory()->instructor()->active()->create([
            'name' => 'John Instructor',
            'email' => 'instructor@example.com',
            'password' => Hash::make('Password123#')
        ]);

        // Create student users
        User::factory()->student()->active()->create([
            'name' => 'Alice Student',
            'email' => 'student@example.com',
            'password' => Hash::make('Password123#')
        ]);

        /* // Create additional random users
        User::factory()->student()->active()->count(15)->create();
        User::factory()->instructor()->active()->count(5)->create();

        // Create some suspended users
        User::factory()->student()->suspended()->count(2)->create();
        User::factory()->instructor()->suspended()->count(1)->create(); */
    }

    /**
     * Create class types
     */
    private function createClassTypes(): void
    {
        $classTypes = [
            [
                'name' => 'Yoga',
                'description' => 'Relaxing yoga sessions for all levels',
                'default_capacity' => 20,
                'min_attendees' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Pilates',
                'description' => 'Core strengthening and flexibility training',
                'default_capacity' => 15,
                'min_attendees' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'CrossFit',
                'description' => 'High-intensity functional fitness',
                'default_capacity' => 12,
                'min_attendees' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Zumba',
                'description' => 'Dance fitness with Latin rhythms',
                'default_capacity' => 25,
                'min_attendees' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Spinning',
                'description' => 'Indoor cycling classes',
                'default_capacity' => 18,
                'min_attendees' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Boxing',
                'description' => 'Combat fitness and self-defense',
                'default_capacity' => 10,
                'min_attendees' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($classTypes as $classType) {
            ClassType::create($classType);
        }

        // Create some additional random class types
        ClassType::factory()->count(4)->create();
    }

    /**
     * Create class schedules
     */
    private function createClassSchedules(): void
    {
        $instructors = User::where('role', RoleCode::INSTRUCTOR)->get();
        $classTypes = ClassType::all();

        // Create schedules for each instructor and class type combination
        foreach ($instructors as $instructor) {
            foreach ($classTypes as $classType) {
                // Create 2-4 schedules per instructor per class type
                $scheduleCount = fake()->numberBetween(2, 4);

                for ($i = 0; $i < $scheduleCount; $i++) {
                    ClassSchedule::factory()->create([
                        'class_type_id' => $classType->id,
                        'instructor_id' => $instructor->id,
                        'weekday' => fake()->numberBetween(0, 6),
                        'start_time' => fake()->time('H:i', '18:00'),
                        'end_time' => fake()->time('H:i', '20:00'),
                        'capacity' => fake()->numberBetween($classType->min_attendees, $classType->default_capacity + 5),
                        'min_attendees' => $classType->min_attendees,
                        'is_active' => fake()->boolean(90),
                    ]);
                }
            }
        }

        // Create some additional random schedules
        ClassSchedule::factory()->count(10)->create();
    }

    /**
     * Create class sessions
     */
    private function createClassSessions(): void
    {
        $instructors = User::where('role', RoleCode::INSTRUCTOR)->get();
        $classTypes = ClassType::all();
        $schedules = ClassSchedule::where('is_active', true)->get();

        // Create sessions for the next 2 months
        foreach ($instructors as $instructor) {
            foreach ($classTypes as $classType) {
                // Create 8-12 sessions per instructor per class type
                $sessionCount = fake()->numberBetween(8, 12);

                for ($i = 0; $i < $sessionCount; $i++) {
                    $startAt = fake()->dateTimeBetween('now', '+2 months');
                    $endAt = fake()->dateTimeBetween($startAt, (clone $startAt)->modify('+2 hours'));

                    $matchingSchedules = $schedules->where('instructor_id', $instructor->id)
                        ->where('class_type_id', $classType->id);

                    $schedule = $matchingSchedules->isNotEmpty()
                        ? $matchingSchedules->random()
                        : null;

                    ClassSession::factory()->create([
                        'class_type_id' => $classType->id,
                        'instructor_id' => $instructor->id,
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                        'capacity' => fake()->numberBetween($classType->min_attendees, $classType->default_capacity + 5),
                        'min_attendees' => $classType->min_attendees,
                        'status' => fake()->randomElement(['scheduled', 'confirmed']),
                        'generated_from_schedule_id' => $schedule ? $schedule->id : null,
                    ]);
                }
            }
        }

        // Create some past sessions
        ClassSession::factory()->past()->count(20)->create();

        // Create some completed sessions
        ClassSession::factory()->completed()->count(15)->create();

        // Create some canceled sessions
        ClassSession::factory()->canceledLowAttendance()->count(5)->create();
    }

    /**
     * Create reservations
     */
    private function createReservations(): void
    {
        $students = User::where('role', RoleCode::STUDENT)->get();
        $sessions = ClassSession::all();

        // Create reservations for each student
        foreach ($students as $student) {
            // Each student makes 3-8 reservations
            $reservationCount = fake()->numberBetween(3, 8);
            $maxReservations = min($reservationCount, $sessions->count());
            $selectedSessions = $sessions->random($maxReservations);

            foreach ($selectedSessions as $session) {
                $bookedAt = fake()->dateTimeBetween('-1 month', 'now');

                // Ensure cancellation deadline is between booked_at and session start
                $cancellationDeadline = fake()->dateTimeBetween(
                    $bookedAt,
                    $session->start_at > now() ? $session->start_at : now()
                );

                $status = fake()->randomElement(['booked', 'attended', 'canceled_by_user', 'no_show']);

                $reservationData = [
                    'session_id' => $session->id,
                    'user_id' => $student->id,
                    'status' => $status,
                    'booked_at' => $bookedAt,
                    'cancellation_deadline' => $cancellationDeadline,
                ];

                // Add conditional fields based on status
                if (in_array($status, ['canceled_by_user', 'canceled_by_system'])) {
                    $reservationData['canceled_at'] = fake()->dateTimeBetween($bookedAt, 'now');
                }

                if ($status === 'attended') {
                    $reservationData['checked_in_at'] = fake()->dateTimeBetween($bookedAt, 'now');
                }

                Reservation::create($reservationData);
            }
        }

        // Create some additional random reservations
        Reservation::factory()->count(30)->create();
    }
}
