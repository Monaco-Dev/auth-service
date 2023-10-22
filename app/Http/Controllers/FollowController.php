<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FollowServiceInterface;
use App\Http\Requests\Follow\{
    FollowRequest,
    UnfollowRequest,
    SearchFollowingRequest,
    SearchFollowersRequest
};
use App\Models\User;

class FollowController extends Controller
{
    /**
     * The service instance.
     *
     * @var \App\Services\Contracts\FollowServiceInterface
     */
    protected $service;

    /**
     * Create the controller instance and resolve its service.
     * 
     * @param \App\Services\Contracts\FollowServiceInterface $service
     */
    public function __construct(FollowServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Follow\FollowRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function follow(FollowRequest $request, User $user)
    {
        return $this->service->follow($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Follow\UnfollowRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function unfollow(UnfollowRequest $request, User $user)
    {
        return $this->service->unfollow($user);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Follow\SearchFollowingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowing(SearchFollowingRequest $request)
    {
        return $this->service->searchFollowing($request->validated());
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  \App\Http\Requests\Follow\SearchFollowersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowers(SearchFollowersRequest $request)
    {
        return $this->service->searchFollowers($request->validated());
    }
}
