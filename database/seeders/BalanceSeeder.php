<?php

namespace Database\Seeders;

use App\Models\Coin;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Database\Seeder;

class BalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users      = User::query()->get();
        $goldCoin   = Coin::query()->where('symbol', 'GOLD')->first();
        $rialCoin   = Coin::query()->where('symbol', 'IRR')->first();

        foreach ($users as $user) {

            if ($user->email === 'ahmad@example.com') {
                $goldAmount = 3;
                $rialAmount = 30_000_000 * 10;
            } elseif ($user->email === 'reza@example.com') {
                $goldAmount = 7;
                $rialAmount = 50_000_000 * 10;
            } elseif ($user->email === 'akbar@example.com') {
                $goldAmount = 10;
                $rialAmount = 15_000_000 * 10;
            } else {
                $goldAmount = mt_rand(1, 20) + (mt_rand(0, 999) / 1000);
                $rialAmount = mt_rand(1_000_000 * 10, 100000000 * 10);
            }

            Balance::query()->updateOrCreate([
                'user_id'           => $user->id,
                'coin_id'           => $goldCoin->id,
            ], [
                'total_amount'      => $goldAmount,
                'available_amount'  => $goldAmount,
                'locked_amount'     => 0,
            ]);

            Balance::query()->updateOrCreate([
                'user_id'           => $user->id,
                'coin_id'           => $rialCoin->id,
            ], [
                'total_amount'      => $rialAmount,
                'available_amount'  => $rialAmount,
                'locked_amount'     => 0,
            ]);
        }
    }
}
