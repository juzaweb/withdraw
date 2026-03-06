<?php

namespace Juzaweb\Modules\Withdraw\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Withdraw\Models\WithdrawMethod;
use Juzaweb\Modules\Withdraw\Tests\TestCase;

class WithdrawMethodControllerTest extends TestCase
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

    public function testIndexWithdrawMethods()
    {
        $response = $this->get(admin_url('withdraw-methods'));

        $response->assertStatus(200);
    }

    public function testCreateWithdrawMethod()
    {
        $response = $this->get(admin_url('withdraw-methods/create'));

        $response->assertStatus(200);
    }

    public function testStoreWithdrawMethod()
    {
        $response = $this->postJson(admin_url('withdraw-methods'), [
            'name' => 'Bank Transfer',
            'description' => 'Transfer to bank account',
            'min_amount' => 10,
            'active' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('withdraw_methods', [
            'name' => 'Bank Transfer',
            'min_amount' => 10,
        ]);
    }

    public function testStoreWithdrawMethodRequiresName()
    {
        $response = $this->postJson(admin_url('withdraw-methods'), [
            'description' => 'Transfer to bank account',
            'min_amount' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateWithdrawMethod()
    {
        $method = WithdrawMethod::create([
            'name' => 'Old Method',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $response = $this->putJson(admin_url('withdraw-methods/' . $method->id), [
            'name' => 'Updated Method',
            'min_amount' => 15,
            'active' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('withdraw_methods', [
            'id' => $method->id,
            'name' => 'Updated Method',
            'min_amount' => 15,
        ]);
    }


    public function testBulkDeleteWithdrawMethods()
    {
        $method1 = WithdrawMethod::create([
            'name' => 'Method 1',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $method2 = WithdrawMethod::create([
            'name' => 'Method 2',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $response = $this->postJson(admin_url('withdraw-methods/bulk'), [
            'ids' => [$method1->id, $method2->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('withdraw_methods', ['id' => $method1->id]);
        $this->assertDatabaseMissing('withdraw_methods', ['id' => $method2->id]);
    }

    public function testBulkActivateWithdrawMethods()
    {
        $method1 = WithdrawMethod::create([
            'name' => 'Method 1',
            'min_amount' => 5,
            'active' => 0,
        ]);

        $method2 = WithdrawMethod::create([
            'name' => 'Method 2',
            'min_amount' => 5,
            'active' => 0,
        ]);

        $response = $this->postJson(admin_url('withdraw-methods/bulk'), [
            'ids' => [$method1->id, $method2->id],
            'action' => 'activate',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('withdraw_methods', ['id' => $method1->id, 'active' => 1]);
        $this->assertDatabaseHas('withdraw_methods', ['id' => $method2->id, 'active' => 1]);
    }

    public function testBulkDeactivateWithdrawMethods()
    {
        $method1 = WithdrawMethod::create([
            'name' => 'Method 1',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $method2 = WithdrawMethod::create([
            'name' => 'Method 2',
            'min_amount' => 5,
            'active' => 1,
        ]);

        $response = $this->postJson(admin_url('withdraw-methods/bulk'), [
            'ids' => [$method1->id, $method2->id],
            'action' => 'deactivate',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('withdraw_methods', ['id' => $method1->id, 'active' => 0]);
        $this->assertDatabaseHas('withdraw_methods', ['id' => $method2->id, 'active' => 0]);
    }
}
