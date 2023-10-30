<?php

namespace App\Repositories;

use App\Models\Slug;
use App\Repositories\Contracts\SlugRepositoryInterface;

class SlugRepository extends Repository implements SlugRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Slug
     */
    public function __construct(Slug $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve user profile via slug.
     * 
     * @param string $slug
     * @return App\Models\User
     */
    public function profile($slug)
    {
        $model = $this->model
            ->whereSlug($slug)
            ->first();

        if (!$model) return null;

        return $model
            ->user()
            ->withRelations()
            ->verified()
            ->first();
    }
}
