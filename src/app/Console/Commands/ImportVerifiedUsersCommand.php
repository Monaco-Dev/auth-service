<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\User;
use App\Notifications\LicenseNotification;

class ImportVerifiedUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV file of verified users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $files = glob(storage_path('app') . '/*.csv');

            $imports = [];
            $unverified = [];
            $verified = [];

            foreach ($files as $file) {
                $imports[] = $this->csvToArray($file);
            }

            foreach ($imports as $users) {
                foreach ($users as $user) {
                    $model = User::whereId($user['ID'])->first();
                    $type = Str::lower($user['License Type']);
                    $isVerified = Str::lower($user['Is Verified']);

                    if (
                        in_array($isVerified, ['true', 'yes']) &&
                        in_array($type, ['sales', 'broker'])
                    ) {
                        $model->license->update([
                            'verified_at' => now(),
                            'type' => $type
                        ]);

                        $verified[] = $user['ID'];
                    } else {
                        Storage::disk('gcs')->delete($model->license->file);

                        $model->license->forceDelete();

                        $unverified[] = $user['ID'];
                    }
                }
            }

            $unverifiedUsers = User::whereIn('id', $unverified)->cursor();

            // $unverifiedUsers->each(function ($user) {
            //     $user->notify(new LicenseNotification($user, false));
            // });

            $verifiedUsers = User::whereIn('id', $verified)->cursor();

            $verifiedUsers->each(function ($user) {
                $user->notify(new LicenseNotification($user, true));
            });

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Convert CSV file to array
     *
     * @param string $file
     * @return array
     */
    private function csvToArray($file)
    {
        $header = null;
        $data = array();
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
