<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_can_be_created(): void
    {
        $user = User::factory()->create();

        $webhook = Webhook::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://example.com/webhook',
        ]);

        $this->assertDatabaseHas('webhooks', [
            'user_id' => $user->id,
            'url' => 'https://example.com/webhook',
        ]);
    }

    public function test_webhook_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $webhook = Webhook::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $webhook->user);
        $this->assertEquals($user->id, $webhook->user->id);
    }

    public function test_webhook_casts_attributes_correctly(): void
    {
        $webhook = new Webhook();
        $casts = $webhook->getCasts();

        $this->assertEquals('array', $casts['events']);
        $this->assertEquals('datetime', $casts['last_triggered_at']);
    }

    public function test_webhook_default_retry_count_is_three(): void
    {
        $webhook = Webhook::factory()->create();

        $this->assertEquals(3, $webhook->retry_count);
    }

    public function test_webhook_default_status_is_active(): void
    {
        $webhook = Webhook::factory()->create();

        $this->assertEquals('active', $webhook->status);
    }

    public function test_webhook_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'url', 'events', 'secret', 'retry_count',
            'status', 'last_triggered_at',
        ];

        $webhook = new Webhook();
        $this->assertEquals($fillable, $webhook->getFillable());
    }
}
