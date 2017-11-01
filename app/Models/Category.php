<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Category
 *
 * @property int $id
 * @property string $title
 * @property string $thumb
 * @property boolean $is_common
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 *
 * @package App
 */

class Category extends Model
{

    protected $fillable = [
        'title',
        'thumb',
        'is_common'
    ];


    // RELATIONS

    public function transactions()
    {
        return $this->hasMany(\Transaction::class);
    }

    public function users()
    {
        return $this->belongsToMany(\User::class);
    }

}
