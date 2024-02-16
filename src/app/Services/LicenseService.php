<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

use App\Repositories\Contracts\LicenseRepositoryInterface;
use App\Services\Contracts\LicenseServiceInterface;
use App\Services\Support\GoogleCloudStorage;

class LicenseService extends Service implements LicenseServiceInterface
{
    /**
     * Google Cloud Storage Service.
     * 
     * @var \App\Services\Support\GoogleCloudStorage
     */
    protected $googleCloudStorage;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\LicenseRepositoryInterface
     * @param App\Services\Support\GoogleCloudStorage
     */
    public function __construct(
        LicenseRepositoryInterface $repository,
        GoogleCloudStorage $googleCloudStorage
    ) {
        $this->repository = $repository;
        $this->googleCloudStorage = $googleCloudStorage;
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(array $request)
    {
        DB::beginTransaction();

        try {
            $model = auth()->user()->license;
            $userId = auth()->user()->id;

            $file = Arr::get($request, 'file');

            $fileName = $userId . '_' . time() . '.' . $file->getClientOriginalExtension();

            $cloudPath = 'Licenses/' . $fileName;

            $file->storeAs('temp', $fileName, 'local');

            if ($model) {
                $this->googleCloudStorage->delete($model->file);

                $model->fill($request);

                if ($model->isDirty()) {
                    $model->verified_at = null;
                }

                $model->save();

                $userId = $model->user_id;
            }

            $this->googleCloudStorage->upload($fileName, $cloudPath);

            Storage::disk('local')->delete('temp/' . $fileName);

            Arr::set($request, 'file', $cloudPath);

            $this->repository->updateOrCreate(
                ['user_id' => $userId],
                $request
            );

            DB::commit();

            return response()->json(true);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
