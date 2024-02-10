<?php

namespace App\Services\Contracts;

interface SocialiteServiceInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  array $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function callback(array $request);

    /**
     * Display the specified resource.
     *
     * @param array $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function redirect(array $request);

    /**
     * Store a newly created resource in storage.
     * 
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function login(array $request);
}
