<?php

namespace Tests\Unit\Models;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    use RefreshDatabase;

    public function test_referral_can_be_created(): void
    {
        $referrer = User::factory()->create();
        $referred = User::factory()->create();

        $referral = Referral::factory()->create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
        ]);

        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
        ]);
    }

    public function test_referral_auto_generates_code(): void
    {
        $referral = Referral::factory()->create(['code' => null]);

        $this->assertNotNull($referral->code);
        $this->assertEquals(8, strlen($referral->code));
    }

    public function test_referral_belongs_to_referrer(): void
    {
        $referrer = User::factory()->create();
        $referral = Referral::factory()->create(['referrer_id' => $referrer->id]);

        $this->assertInstanceOf(User::class, $referral->referrer);
        $this->assertEquals($referrer->id, $referral->referrer->id);
    }

    public function test_referral_belongs_to_referred_user(): void
    {
        $referred = User::factory()->create();
        $referral = Referral::factory()->create(['referred_id' => $referred->id]);

        $this->assertInstanceOf(User::class, $referral->referred);
        $this->assertEquals($referred->id, $referral->referred->id);
    }

    public function test_referral_code_is_unique(): void
    {
        Referral::factory()->create(['code' => 'UNIQUE12']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Referral::factory()->create(['code' => 'UNIQUE12']);
    }

    public function test_referral_default_status_is_pending(): void
    {
        $referral = Referral::factory()->create();

        $this->assertEquals('pending', $referral->status);
    }

    public function test_referral_default_reward_points_is_zero(): void
    {
        $referral = Referral::factory()->create();

        $this->assertEquals(0, $referral->reward_points);
    }

    public function test_referral_has_fillable_attributes(): void
    {
        $fillable = [
            'referrer_id', 'referred_id', 'code', 'status', 'reward_points', 'rewarded_at',
        ];

        $referral = new Referral();
        $this->assertEquals($fillable, $referral->getFillable());
    }

    public function test_referral_casts_attributes_correctly(): void
    {
        $referral = new Referral();
        $casts = $referral->getCasts();

        $this->assertEquals('datetime', $casts['rewarded_at']);
    }
}
