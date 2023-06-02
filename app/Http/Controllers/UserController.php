<?php

namespace App\Http\Controllers;

use App\Services\Contracts\UserServiceInterface;
use App\Http\Requests\User\{
    SearchRequest,
    UpdateRequest,
};

class UserController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\UserServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\UserServiceInterface $service
     */
    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\User\UpdateRequest  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\User\SearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request)
    {
        return $this->service->search($request->validated());
    }
}
