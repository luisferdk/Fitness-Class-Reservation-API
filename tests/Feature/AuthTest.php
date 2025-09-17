<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_can_register_and_login(): void
  {

    $this->postJson('/api/register', [
      'name' => 'User',
      'email' => 'user@example.com',
      'password' => 'Pasword123#',
    ])->assertCreated();


    $login = $this->postJson('/api/login', [
      'email' => 'user@example.com',
      'password' => 'Pasword123#',
    ])->assertOk();

    $token = $login->json('token');
    $this->assertNotNull($token);


    $this->getJson('/api/me', [
      'Authorization' => "Bearer {$token}"
    ])->assertOk();
  }

  public function test_create_user_and_prevent_duplicate(): void
  {

    $this->postJson('/api/register', [
      'name' => 'User',
      'email' => 'user@example.com',
      'password' => 'Password123#',
      'password_confirmation' => 'Password123#',
    ])->assertCreated();


    $this->postJson('/api/register', [
      'name' => 'User',
      'email' => 'user@example.com',
      'password' => 'Password123#',
      'password_confirmation' => 'Password123#',
    ])->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  }
}
