<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Notifications\ReviewLicenseNotification;

class ExportUnverifiedUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export CSV file of unverified users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with(['license'])
            ->whereNotNull('email_verified_at')
            ->whereNull('deactivated_at')
            ->whereHas('license', function ($query) {
                $query->whereNull('verified_at')
                    ->where('expiration_date', '>', now());
            })
            ->cursor();

        if (!$users->count()) exit;

        $this->info($users->count() . ' users found');

        $outFile = fopen(storage_path('app') . '/data.csv', 'w');

        fputcsv($outFile, [
            'ID' => 'ID',
            'Name' => 'Name',
            'Email' => 'Email',
            'Phone Number' => 'Phone Number',
            'License Number' => 'License Number',
            'License Expiration Date' => 'License Expiration Date',
            'License Type' => 'License Type',
            'Is Verified' => 'Is Verified',
            'Link' => 'Link',
        ]);

        foreach ($users as $user) {
            fputcsv($outFile, [
                'ID' => $user->id,
                'Name' => $user->full_name,
                'Email' => $user->email,
                'Phone Number' => $user->phone_number,
                'License Number' => $user->license->license_number,
                'License Expiration Date' => $user->license->expiration_date,
                'License Type' => null,
                'Is Verified' => false,
                'Link' => $user->license->url,
            ]);
        }

        fclose($outFile);

        $filename = date('d-m-Y') . '.csv';

        $path = Storage::disk('gcs')->putFileAs('Internals', new File(storage_path('app') . "/data.csv"), $filename);

        Storage::disk('local')->delete('data.csv');

        $link = Storage::disk('gcs')->temporaryUrl(
            $path,
            now()->addHours(12),
            [
                'ResponseContentType' => 'application/octet-stream',
                'ResponseContentDisposition' => "attachment; filename=$filename",
            ]
        );

        Notification::route('mail', [
            config('mail.from.address') => 'Admin'
        ])->notify(new ReviewLicenseNotification($link));
    }
}
