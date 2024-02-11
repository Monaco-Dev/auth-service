<?php

namespace App\Http\Controllers;

use App\Services\Contracts\SocialiteServiceInterface;
use App\Http\Requests\Socialite\{
    SocialiteRequest,
    LoginRequest
};

class SocialiteController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\SocialiteServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\SocialiteServiceInterface $service
     */
    public function __construct(SocialiteServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\Socialite\SocialiteRequest $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function callback(SocialiteRequest $request)
    {
        return $this->service->callback($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Http\Requests\Socialite\SocialiteRequest $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function redirect(SocialiteRequest $request)
    {
        return $this->service->redirect($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\Socialite\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        return $this->service->login($request->validated());
    }
}
