<?php

namespace Tests\Feature;

use App\Models\Coin;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $baseCoin;
    protected $quoteCoin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();

        // Create coins
        $this->baseCoin = Coin::create([
            'name' => 'طلا',
            'name_en' => 'Gold',
            'symbol' => 'GOLD',
            'is_fiat' => false,
            'is_active' => true,
        ]);

        $this->quoteCoin = Coin::create([
            'name' => 'ریال',
            'name_en' => 'Rial',
            'symbol' => 'IRR',
            'is_fiat' => true,
            'is_active' => true,
        ]);

        // Add balances for the user
        Balance::create([
            'user_id' => $this->user->id,
            'coin_id' => $this->baseCoin->id,
            'total_amount' => 10,
            'available_amount' => 10,
            'locked_amount' => 0,
        ]);

        Balance::create([
            'user_id' => $this->user->id,
            'coin_id' => $this->quoteCoin->id,
            'total_amount' => 100000000,
            'available_amount' => 100000000,
            'locked_amount' => 0,
        ]);
    }

    /**
     * Test creating a buy order.
     */
    public function test_create_buy_order(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/orders', [
                'base_coin_id' => $this->baseCoin->id,
                'quote_coin_id' => $this->quoteCoin->id,
                'type' => 'BUY',
                'amount' => 2,
                'price' => 10000000,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'base_coin_id',
                    'quote_coin_id',
                    'type',
                    'amount',
                    'remaining_amount',
                    'price',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'user_id' => $this->user->id,
                    'base_coin_id' => $this->baseCoin->id,
                    'quote_coin_id' => $this->quoteCoin->id,
                    'type' => 'BUY',
                    'amount' => '2.00000000',
                    'price' => '10000000.00000000',
                    'status' => 'OPEN',
                ],
            ]);
    }

    /**
     * Test creating a sell order.
     */
    public function test_create_sell_order(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/orders', [
                'base_coin_id' => $this->baseCoin->id,
                'quote_coin_id' => $this->quoteCoin->id,
                'type' => 'SELL',
                'amount' => 2,
                'price' => 10000000,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'base_coin_id',
                    'quote_coin_id',
                    'type',
                    'amount',
                    'remaining_amount',
                    'price',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'user_id' => $this->user->id,
                    'base_coin_id' => $this->baseCoin->id,
                    'quote_coin_id' => $this->quoteCoin->id,
                    'type' => 'SELL',
                    'amount' => '2.00000000',
                    'price' => '10000000.00000000',
                    'status' => 'OPEN',
                ],
            ]);
    }

    /**
     * Test matching orders (partial matching).
     */
    public function test_matching_orders(): void
    {
        // Create another user
        $anotherUser = User::factory()->create();

        // Add balances for second user
        Balance::create([
            'user_id' => $anotherUser->id,
            'coin_id' => $this->baseCoin->id,
            'total_amount' => 10,
            'available_amount' => 10,
            'locked_amount' => 0,
        ]);

        Balance::create([
            'user_id' => $anotherUser->id,
            'coin_id' => $this->quoteCoin->id,
            'total_amount' => 100000000,
            'available_amount' => 100000000,
            'locked_amount' => 0,
        ]);

        // First user creates a sell order for 5 grams
        $this->actingAs($this->user)
            ->postJson('/api/orders', [
                'base_coin_id' => $this->baseCoin->id,
                'quote_coin_id' => $this->quoteCoin->id,
                'type' => 'SELL',
                'amount' => 5,
                'price' => 10000000,
            ]);

        // Second user creates a buy order for 2 grams
        $response = $this->actingAs($anotherUser)
            ->postJson('/api/orders', [
                'base_coin_id' => $this->baseCoin->id,
                'quote_coin_id' => $this->quoteCoin->id,
                'type' => 'BUY',
                'amount' => 2,
                'price' => 10000000,
            ]);

        // Check that the buy order is now completed
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'COMPLETED',
                    'remaining_amount' => '0.00000000',
                ],
            ]);

        // Check that the first user's sell order is still open but partially filled
        $this->actingAs($this->user)
            ->getJson('/api/orders')
            ->assertOk()
            ->assertJsonPath('data.data.0.status', 'OPEN')
            ->assertJsonPath('data.data.0.remaining_amount', '3.00000000');

        // Check that a trade record has been created
        $this->actingAs($this->user)
            ->getJson('/api/trades')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.amount', '2.00000000')
            ->assertJsonPath('data.data.0.price', '10000000.00000000');

        // Verify user balances have been updated correctly
        $this->actingAs($this->user)
            ->getJson('/api/balances/' . $this->baseCoin->id)
            ->assertOk()
            ->assertJsonPath('data.locked_amount', '3.00000000')
            ->assertJsonPath('data.total_amount', '8.00000000');

        $this->actingAs($anotherUser)
            ->getJson('/api/balances/' . $this->baseCoin->id)
            ->assertOk()
            ->assertJsonPath('data.available_amount', '12.00000000')
            ->assertJsonPath('data.total_amount', '12.00000000');
    }
} 