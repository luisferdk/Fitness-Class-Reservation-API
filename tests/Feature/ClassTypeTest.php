<?php

namespace Tests\Feature;

use App\Models\ClassType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClassTypeTest extends TestCase
{
  use RefreshDatabase;

  public function test_student_can_list_and_show_class_types(): void
  {
    $student = User::factory()->create();
    Sanctum::actingAs($student);

    $classTypes = ClassType::factory()->count(3)->create();

    // index → pagination + each data element structure
    $this->getJson('/api/class-types')
      ->assertOk()
      ->assertJsonStructure([
        'current_page',
        'data' => [
          '*' => [
            'id',
            'name',
            'description',
            'default_capacity',
            'min_attendees',
            'is_active',
            'created_at',
            'updated_at',
          ],
        ],
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links' => [
          '*' => [
            'url',
            'label',
            'active',
          ],
        ],
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total',
      ]);
  }

  public function test_unauthenticated_users_cannot_create_update_or_delete(): void
  {
    $ct = ClassType::factory()->create();

    // store
    $this->postJson('/api/class-types', [
      'name' => 'Yoga',
      'default_capacity' => 10,
      'min_attendees' => 2,
      'is_active' => true,
    ])->assertStatus(401);

    // update
    $this->putJson("/api/class-types/{$ct->id}", [
      'default_capacity' => 20,
    ])->assertStatus(401);

    // delete
    $this->deleteJson("/api/class-types/{$ct->id}")
      ->assertStatus(401);
  }

  public function test_non_admin_cannot_create_update_or_delete(): void
  {
    $student = User::factory()->create(); // default role = student
    Sanctum::actingAs($student);

    $ct = ClassType::factory()->create();

    $this->postJson('/api/class-types', [
      'name' => 'Pilates',
      'default_capacity' => 12,
      'min_attendees' => 3,
    ])->assertStatus(403);

    $this->putJson("/api/class-types/{$ct->id}", [
      'default_capacity' => 25,
    ])->assertStatus(403);

    $this->deleteJson("/api/class-types/{$ct->id}")
      ->assertStatus(403);
  }

  public function test_admin_can_create_class_type(): void
  {
    $admin = User::factory()->state(['role' => 'admin'])->create();
    Sanctum::actingAs($admin);

    $payload = [
      'name' => 'Functional Training',
      'description' => 'Strength & conditioning',
      'default_capacity' => 16,
      'min_attendees' => 4,
      'is_active' => true,
    ];

    $this->postJson('/api/class-types', $payload)
      ->assertCreated()
      ->assertJsonFragment([
        'name' => 'Functional Training',
        'default_capacity' => 16,
        'min_attendees' => 4,
        'is_active' => true,
      ]);

    $this->assertDatabaseHas('class_types', [
      'name' => 'Functional Training',
      'default_capacity' => 16,
      'min_attendees' => 4,
      'is_active' => true,
    ]);
  }

  public function test_admin_store_validates_min_attendees_not_greater_than_capacity(): void
  {
    $admin = User::factory()->state(['role' => 'admin'])->create();
    Sanctum::actingAs($admin);

    $this->postJson('/api/class-types', [
      'name' => 'Bootcamp',
      'default_capacity' => 8,
      'min_attendees' => 10, // invalid
    ])->assertStatus(422)
      ->assertJsonValidationErrors(['min_attendees']);
  }

  public function test_admin_can_update_class_type(): void
  {
    $admin = User::factory()->state(['role' => 'admin'])->create();
    Sanctum::actingAs($admin);

    $ct = ClassType::factory()->create([
      'name' => 'Stretch',
      'default_capacity' => 10,
      'min_attendees' => 2,
    ]);

    $this->putJson("/api/class-types/{$ct->id}", [
      'name' => 'Stretching',
      'default_capacity' => 14,
      'min_attendees' => 3,
    ])->assertOk()
      ->assertJsonFragment([
        'name' => 'Stretching',
        'default_capacity' => 14,
        'min_attendees' => 3,
      ]);

    $this->assertDatabaseHas('class_types', [
      'id' => $ct->id,
      'name' => 'Stretching',
      'default_capacity' => 14,
      'min_attendees' => 3,
    ]);
  }

  public function test_admin_update_validates_min_attendees_not_greater_than_capacity(): void
  {
    $admin = User::factory()->state(['role' => 'admin'])->create();
    Sanctum::actingAs($admin);

    $ct = ClassType::factory()->create([
      'default_capacity' => 10,
      'min_attendees' => 2,
    ]);

    $this->putJson("/api/class-types/{$ct->id}", [
      'min_attendees' => 11, // invalid
    ])->assertStatus(422)
      ->assertJsonValidationErrors(['min_attendees']);
  }

  public function test_admin_can_delete_class_type(): void
  {
    $admin = User::factory()->state(['role' => 'admin'])->create();
    Sanctum::actingAs($admin);

    $ct = ClassType::factory()->create();

    $this->deleteJson("/api/class-types/{$ct->id}")
      ->assertOk()
      ->assertJsonFragment(['message' => 'Deleted']);

    $this->assertDatabaseMissing('class_types', ['id' => $ct->id]);
  }
}
