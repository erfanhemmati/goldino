<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Ahmad',
                'email'     => 'ahmad@example.com',
                'password'  => bcrypt('123456789'),
            ],
            [
                'name'      => 'Reza',
                'email'     => 'reza@example.com',
                'password'  => bcrypt('123456789'),
            ],
            [
                'name'      => 'Akbar',
                'email'     => 'akbar@example.com',
                'password'  => bcrypt('123456789'),
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate([
                'email'     => $user['email'],
            ], $user);
        }
    }
}
