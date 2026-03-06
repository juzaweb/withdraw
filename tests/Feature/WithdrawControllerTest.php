<?php

namespace Juzaweb\Modules\Withdraw\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Withdraw\Models\Withdraw;
use Juzaweb\Modules\Withdraw\Models\WithdrawMethod;
use Juzaweb\Modules\Withdraw\Tests\TestCase;

class WithdrawControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
    }

    public function testIndexWithdraws()
    {
        $response = $this->get(admin_url('withdraws'));

        $response->assertStatus(200);
    }

    public function testBulkDeleteWithdraws()
    {
        $method = WithdrawMethod::create([
            'name' => 'Method 1',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $withdraw1 = Withdraw::create([
            'withdrawable_id' => $this->user->id,
            'withdrawable_type' => User::class,
            'method_id' => $method->id,
            'amount' => 10,
            'type' => 'user',
            'status' => 'pending',
            'meta' => [],
        ]);

        $withdraw2 = Withdraw::create([
            'withdrawable_id' => $this->user->id,
            'withdrawable_type' => User::class,
            'method_id' => $method->id,
            'amount' => 20,
            'type' => 'user',
            'status' => 'pending',
            'meta' => [],
        ]);

        $response = $this->postJson(admin_url('withdraws/bulk'), [
            'ids' => [$withdraw1->id, $withdraw2->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('withdraws', ['id' => $withdraw1->id]);
        $this->assertDatabaseMissing('withdraws', ['id' => $withdraw2->id]);
    }
}
