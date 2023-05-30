<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Connection;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()
            ->hasBrokerLicense()
            ->hasSocials()
            ->count(5)
            ->create();

        $users->each(function ($user) use ($users) {
            $users->except(['id' => $user->id])->each(function ($connection) use ($user) {
                Connection::factory()->create([
                    'user_id' => $user->id,
                    'connection_user_id' => $connection->id
                ]);
            });
        });
    }
}
