<?php

namespace Tests\Feature;

use App\Enums\RoleCode;
use App\Models\ClassType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClassTypeAsAdminTest extends TestCase
{
  use RefreshDatabase;


  private function loginAsAdmin(): string
  {
    $admin = User::create([
      'name' => 'Admin',
      'email' => 'admin@example.com',
      'password' => Hash::make('Password123#'),
      'role' => RoleCode::ADMIN,
    ]);

    $res = $this->postJson('/api/login', [
      'email' => 'admin@example.com',
      'password' => 'Password123#',
    ])->assertOk();

    return $res->json('token');
  }

  private function authHeaders(string $token): array
  {
    return ['Authorization' => "Bearer {$token}"];
  }


  public function test_admin_can_create_class_type(): void
  {
    $token = $this->loginAsAdmin();

    $payload = [
      'name' => 'Pilates Avanzado',
      'description' => 'Sesiones intensas',
      'default_capacity' => 12,
      'min_attendees' => 2,
      'is_active' => true,
    ];

    $res = $this->postJson('/api/class-types', $payload, $this->authHeaders($token))
      ->assertCreated();

    $this->assertDatabaseHas('class_types', [
      'name' => $payload['name'],
      'default_capacity' => $payload['default_capacity'],
      'min_attendees' => $payload['min_attendees'],
      'is_active' => $payload['is_active'],
    ]);
  }


  public function test_admin_can_view_class_types_index_and_show(): void
  {
    $token = $this->loginAsAdmin();

    $ct = ClassType::create([
      'name' => 'Pilates',
      'description' => 'Base',
      'default_capacity' => 10,
      'min_attendees' => 2,
      'is_active' => true,
    ]);

    $this->getJson('/api/class-types', $this->authHeaders($token))
      ->assertOk()
      ->assertJsonFragment(['name' => 'Pilates']);

    $this->getJson("/api/class-types/{$ct->id}", $this->authHeaders($token))
      ->assertOk()
      ->assertJsonFragment(['name' => 'Pilates']);
  }


  public function test_admin_can_update_class_type(): void
  {
    $token = $this->loginAsAdmin();

    $ct = ClassType::create([
      'name' => 'Boxing',
      'description' => 'Base',
      'default_capacity' => 16,
      'min_attendees' => 2,
      'is_active' => true,
    ]);

    $payload = [
      'name' => 'Boxing Intermedio',
      'description' => 'Mejora técnica',
      'default_capacity' => 18,
      'min_attendees' => 3,
      'is_active' => true,
    ];

    $this->putJson("/api/class-types/{$ct->id}", $payload, $this->authHeaders($token))
      ->assertOk();

    $this->assertDatabaseHas('class_types', [
      'id' => $ct->id,
      'name' => 'Boxing Intermedio',
      'default_capacity' => 18,
      'min_attendees' => 3,
    ]);
  }


  public function test_admin_can_delete_class_type(): void
  {
    $token = $this->loginAsAdmin();

    $ct = ClassType::create([
      'name' => 'Gym',
      'description' => 'Sala general',
      'default_capacity' => 20,
      'min_attendees' => 2,
      'is_active' => true,
    ]);

    $this->deleteJson("/api/class-types/{$ct->id}", [], $this->authHeaders($token))
      ->assertNoContent();

    $this->assertDatabaseMissing('class_types', ['id' => $ct->id]);
  }


  public function test_non_admin_cannot_create_update_or_delete(): void
  {

    $user = User::create([
      'name' => 'Student',
      'email' => 'student@example.com',
      'password' => Hash::make('Password123#'),
      'role' => RoleCode::STUDENT,
    ]);

    $login = $this->postJson('/api/login', [
      'email' => 'student@example.com',
      'password' => 'Password123#',
    ])->assertOk();

    $token = $login->json('token');


    $this->postJson('/api/class-types', [
      'name' => 'X',
      'default_capacity' => 10,
      'min_attendees' => 2,
      'is_active' => true,
    ], $this->authHeaders($token))->assertForbidden();


    $ct = ClassType::create([
      'name' => 'Pilates',
      'description' => null,
      'default_capacity' => 12,
      'min_attendees' => 2,
      'is_active' => true,
    ]);

    $this->putJson("/api/class-types/{$ct->id}", [
      'name' => 'Pilates Edit',
      'default_capacity' => 14,
      'min_attendees' => 3,
      'is_active' => true,
    ], $this->authHeaders($token))->assertForbidden();


    $this->deleteJson("/api/class-types/{$ct->id}", [], $this->authHeaders($token))
      ->assertForbidden();
  }
}
