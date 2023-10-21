<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Http\Resources\SlugResource;
use App\Repositories\Contracts\SlugRepositoryInterface;
use App\Services\Contracts\SlugServiceInterface;

class SlugService extends Service implements SlugServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\SlugRepositoryInterface
     */
    public function __construct(SlugRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function store(array $request)
    {
        $user = Auth::user();

        $new = $this->repository->updateOrCreate(
            [
                'user_id' => $user->id,
                'slug' => Arr::get($request, 'slug')
            ],
            ['is_primary' => true]
        );

        $old = $user->slugs()
            ->where('id', '!=', $new->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if ($old) {
            $old->is_primary = false;
            $old->save();

            $user->slugs()
                ->where('user_id', $user->id)
                ->whereNotIn('id', [$new->id, $old->id])
                ->delete();
        }

        return SlugResource::collection(
            $user->slugs()
                ->where('user_id', $user->id)
                ->get()
        );
    }
}
