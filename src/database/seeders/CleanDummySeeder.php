<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Connection;
use App\Models\ConnectionInvitation;
use App\Models\Follow;
use App\Models\Social;
use App\Models\User;

class CleanDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // $int = 1;

            // for ($int; $int <= 45; $int++) {
            //     $user = User::find($int);

            //     $user->license->forceDelete();

            //     ConnectionInvitation::where('user_id', $int)->orWhere('connection_invitation_user_id', $int)->delete();
            //     Connection::where('user_id', $int)->orWhere('connection_user_id', $int)->delete();
            //     Follow::where('user_id', $int)->orWhere('follow_user_id', $int)->delete();
            //     Social::where('user_id', $int)->delete();

            //     $user->forceDelete();
            // }

            // $users = User::whereIn('id', [72])->get();

            // foreach ($users as $user) {
            //     optional($user->license)->forceDelete();

            //     ConnectionInvitation::where('user_id', $user->id)->orWhere('connection_invitation_user_id', $user->id)->delete();
            //     Connection::where('user_id', $user->id)->orWhere('connection_user_id', $user->id)->delete();
            //     Follow::where('user_id', $user->id)->orWhere('follow_user_id', $user->id)->delete();
            //     Social::where('user_id', $user->id)->delete();

            //     $user->forceDelete();
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
