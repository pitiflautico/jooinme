<?php

namespace Tests\Unit\Models;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_key_can_be_created(): void
    {
        $user = User::factory()->create();

        $apiKey = ApiKey::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test API Key',
        ]);

        $this->assertDatabaseHas('api_keys', [
            'user_id' => $user->id,
            'name' => 'Test API Key',
        ]);
    }

    public function test_api_key_auto_generates_key_with_jm_prefix(): void
    {
        $apiKey = ApiKey::factory()->create(['key' => null]);

        $this->assertNotNull($apiKey->key);
        $this->assertStringStartsWith('jm_', $apiKey->key);
        $this->assertEquals(43, strlen($apiKey->key)); // jm_ + 40 chars
    }

    public function test_api_key_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $apiKey = ApiKey::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $apiKey->user);
        $this->assertEquals($user->id, $apiKey->user->id);
    }

    public function test_api_key_is_unique(): void
    {
        ApiKey::factory()->create(['key' => 'jm_uniquekey123']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        ApiKey::factory()->create(['key' => 'jm_uniquekey123']);
    }

    public function test_api_key_is_valid_method_returns_true_when_active(): void
    {
        $apiKey = ApiKey::factory()->create([
            'is_active' => true,
            'expires_at' => now()->addMonth(),
        ]);

        $this->assertTrue($apiKey->isValid());
    }

    public function test_api_key_is_valid_method_returns_false_when_inactive(): void
    {
        $apiKey = ApiKey::factory()->create(['is_active' => false]);

        $this->assertFalse($apiKey->isValid());
    }

    public function test_api_key_is_valid_method_returns_false_when_expired(): void
    {
        $apiKey = ApiKey::factory()->create([
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $this->assertFalse($apiKey->isValid());
    }

    public function test_api_key_casts_attributes_correctly(): void
    {
        $apiKey = new ApiKey();
        $casts = $apiKey->getCasts();

        $this->assertEquals('array', $casts['permissions']);
        $this->assertEquals('array', $casts['rate_limits']);
        $this->assertEquals('datetime', $casts['expires_at']);
        $this->assertEquals('datetime', $casts['last_used_at']);
        $this->assertEquals('boolean', $casts['is_active']);
    }

    public function test_api_key_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'name', 'key', 'permissions', 'rate_limits',
            'expires_at', 'last_used_at', 'is_active',
        ];

        $apiKey = new ApiKey();
        $this->assertEquals($fillable, $apiKey->getFillable());
    }
}
