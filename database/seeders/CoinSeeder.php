<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coins = [
            [
                'name'      => 'طلا',
                'name_en'   => 'Gold',
                'symbol'    => 'GOLD',
                'is_fiat'   => false,
                'is_active' => true,
            ],
            [
                'name'      => 'ریال',
                'name_en'   => 'Rial',
                'symbol'    => 'IRR',
                'is_fiat'   => true,
                'is_active' => true,
            ],
        ];

        foreach ($coins as $coin) {
            Coin::query()->updateOrCreate([
                'symbol'    => $coin['symbol'],
            ], $coin);
        }
    }
}
