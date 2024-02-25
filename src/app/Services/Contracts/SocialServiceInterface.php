<?php

namespace App\Services\Contracts;

interface SocialServiceInterface
{
    /**
     * Update or store a newly created resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function sync(array $request);
}
