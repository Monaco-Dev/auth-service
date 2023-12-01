<?php

namespace App\Services\Contracts;

use App\Models\User;

interface FollowServiceInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function follow(User $user);

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function unfollow(User $user);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowing(array $request);

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowers(array $request);
}
