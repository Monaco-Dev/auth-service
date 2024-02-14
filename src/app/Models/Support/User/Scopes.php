<?php

namespace App\Models\Support\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Scopes
{
    /**
     * User must be verified.
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at')
            ->whereNull('deactivated_at')
            ->whereNull('users.deleted_at')
            ->whereHas('license', function ($query) {
                $query->verified();
            });
    }

    /**
     * Append with relationships.
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWithRelations(Builder $query)
    {
        $with = [
            'license'
        ];

        $withCount = [
            'connections' => function ($query) {
                $query->verified();
            },
            'incomingInvites' => function ($query) {
                $query->verified();
            },
            'outgoingInvites' => function ($query) {
                $query->verified();
            },
            'following' => function ($query) {
                $query->verified();
            },
            'followers' => function ($query) {
                $query->verified();
            }
        ];

        return $query->with($with)->withCount($withCount);
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $search = null): Builder
    {
        $id = $id ?? optional(Auth::user())->id;

        return $query->withRelations()
            ->withCount([
                'mutuals' => function ($query) use ($id) {
                    $query->whereHas('connections', function ($query) use ($id) {
                        $query->where('connection_user_id', $id);
                    });
                }
            ])
            ->leftJoin('licenses', 'licenses.user_id', '=', 'users.id')
            ->where(function ($query) use ($search) {
                $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('license_number', 'like', '%' . $search . '%');
            })
            ->verified()
            ->orderBy('mutuals_count', 'desc')
            ->orderByRaw('LOCATE("' . $search . '", CONCAT(first_name, " ", last_name)) desc')
            ->orderByRaw('LOCATE("' . $search . '", email) desc')
            ->orderByRaw('LOCATE("' . $search . '", phone_number) desc')
            ->orderByRaw('LOCATE("' . $search . '", license_number) desc');
    }

    /**
     * Wildcard search query
     * 
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchMutuals(Builder $query): Builder
    {
        $id = $id ?? optional(Auth::user())->id;

        return $query->withCount([
            'mutuals' => function ($query) use ($id) {
                $query->whereHas('connections', function ($query) use ($id) {
                    $query->where('connection_user_id', $id);
                });
            }
        ])
            ->whereHas('mutuals', function ($query) use ($id) {
                $query->whereHas('connections', function ($query) use ($id) {
                    $query->where('connection_user_id', $id);
                });
            })
            ->whereDoesntHave('connections', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->whereDoesntHave('incomingInvites', function ($query) use ($id) {
                $query->where('connection_invitation_user_id', $id);
            })
            ->whereDoesntHave('outgoingInvites', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->whereDoesntHave('following', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->whereDoesntHave('followers', function ($query) use ($id) {
                $query->where('follow_user_id', $id);
            })
            ->verified()
            ->orderBy('mutuals_count', 'desc');
    }
}
