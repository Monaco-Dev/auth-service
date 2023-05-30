<?php

namespace App\Repositories\Support\Traits;

trait ModelResource
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function all()
    {
        return $this->model->cursor();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $request)
    {
        return $this->model->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, bool $findOrFail = true)
    {
        return $findOrFail ? $this->model->findOrFail($id) : $this->model->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int|string $id
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $request)
    {
        $model = $this->model->findOrFail($id);

        $model->update($request);

        return $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int|string $id
     * @return int
     */
    public function delete($id)
    {
        $model = $this->model->findOrFail($id);

        $model->delete();

        return 1;
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param int|string $id
     * @return int
     */
    public function forceDelete($id)
    {
        $model = $this->model->findOrFail($id);

        $model->forceDelete();

        return 1;
    }

    /**
     * Display the specified resource or store a newly created resource in storage.
     *
     * @param array $where
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $where = [], array $data = [])
    {
        return $this->model->firstOrCreate($where, $data);
    }
}
