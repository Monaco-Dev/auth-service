<?php

namespace App\Services\Support\Traits;

trait RepositoryResource
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function index()
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        return $this->repository->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id, bool $findOrFail = true)
    {
        $data = $this->repository->find($id, $findOrFail);

        return isset($data) ? $data : null;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(mixed $model, array $request)
    {
        return $this->repository->update($model, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return int
     */
    public function destroy(mixed $model)
    {
        return $this->repository->delete($model);
    }
}
