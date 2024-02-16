<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

use App\Repositories\Contracts\LicenseRepositoryInterface;
use App\Services\Contracts\LicenseServiceInterface;

class LicenseService extends Service implements LicenseServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\LicenseRepositoryInterface
     */
    public function __construct(LicenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

            if ($model) {
                Storage::disk('gcs')->delete($model->file);

                $model->fill($request);

                if ($model->isDirty()) {
                    $model->verified_at = null;
                }

                $model->save();

                $userId = $model->user_id;
            }

            $file = Arr::get($request, 'file');
            $fileName = $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $storeFile = $file->storeAs('Licenses', $fileName, 'gcs');

            Arr::set($request, 'file', $storeFile);

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
