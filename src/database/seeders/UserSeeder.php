<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()
            ->hasLicense()
            ->count(5)
            ->create();

        $users->each(function ($user, $key) use ($users) {
            // Create connection
            if ($key + 1 <= count($users) - 1) {
                $network = new User($users->toArray()[$key + 1]);

                $user->connections()->attach($network);
                $network->connections()->attach($user);
            }

            // Create outgoing invites
            User::factory()
                ->hasLicense()
                ->count(2)
                ->create()
                ->each(function ($invite) use ($user) {
                    $user->incomingInvites()->attach($invite);
                });

            // Create incoming invites
            User::factory()
                ->hasLicense()
                ->count(2)
                ->create()
                ->each(function ($invite) use ($user) {
                    $invite->incomingInvites()->attach($user);
                });

            // Create outgoing invites
            User::factory()
                ->hasLicense()
                ->count(2)
                ->create()
                ->each(function ($follow) use ($user) {
                    $user->following()->attach($follow);
                });

            // Create incoming follow
            User::factory()
                ->hasLicense()
                ->count(2)
                ->create()
                ->each(function ($follow) use ($user) {
                    $follow->following()->attach($user);
                });
        });
    }
}
