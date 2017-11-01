<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 *
 * @property int $id
 * @property string $title
 * @property float $balance
 *
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 *
 * @mixin  \Illuminate\Database\Eloquent\Model
 */

class Account extends Model
{

    const MORPH_NAME = 'account';

    protected $fillable = [
        'title',
        'balance'
    ];


    // RELATIONS
    public function users()
    {
        return $this->belongsToMany(\User::class)->withPivot('is_owner');
    }

    /**
     * @return \User $owner
     */
    public function owner()
    {
        return $this->users()->wherePivot('is_owner', 1)->first();
    }
}
