<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pocket
 *
 *
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */

class Pocket extends Model
{


    // RELATIONS
    public function users()
    {
        return $this->belongsToMany(\User::class)->withPivot(['is_owner']);
    }
}
