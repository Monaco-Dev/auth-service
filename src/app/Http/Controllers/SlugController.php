<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SlugServiceInterface;
use App\Http\Requests\Slug\{
    StoreRequest,
    DestroyRequest
};
use App\Models\Slug;

class SlugController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\SlugServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\SlugServiceInterface $service
     */
    public function __construct(SlugServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Slug\StoreRequest  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Slug\DestroyRequest  $request
     * @param  \App\Models\Slug $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Slug $slug)
    {
        return $this->service->destroy($slug);
    }
}
