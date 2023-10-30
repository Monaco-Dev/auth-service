<?php

namespace App\Repositories\Support\BaseContracts;

interface AllInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function all();
}
