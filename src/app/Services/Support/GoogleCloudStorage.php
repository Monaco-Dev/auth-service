<?php

namespace App\Services\Support;

use Google\Cloud\Storage\StorageClient;

class GoogleCloudStorage
{
    protected $storage, $bucket;

    /**
     * Create the class instance.
     */
    public function __construct()
    {
        $this->storage = new StorageClient([
            'projectId' => config('filesystems.disks.gcs.project_id'),
            'keyFile' => config('filesystems.disks.gcs.key_file'),
        ]);

        $this->bucket = $this->storage->bucket(config('filesystems.disks.gcs.bucket'));
    }

    /**
     * @param string $cloudPath
     */
    public function delete($cloudPath)
    {
        try {
            return $this->bucket->object($cloudPath)->delete();
        } catch (\Exception $e) {
            //
        }

        return null;
    }

    /**
     * @param string $fileName
     * @param string $cloudPath
     * @param bool $public
     * @return mixed
     */
    public function upload($fileName, $cloudPath, $public = true)
    {
        $params = ['name' => $cloudPath];

        if ($public) $params['predefinedAcl'] = 'publicRead';

        return $this->bucket->upload(
            fopen(storage_path('app') . '/temp/' . $fileName, 'r'),
            $params
        );
    }
}
