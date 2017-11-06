<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 *  Model User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $login
 * @property string $password
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pocket[] $pockets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 *
 * @mixin  \Illuminate\Database\Eloquent\Model
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // RELATIONS

    public function accounts()
    {
        return $this->belongsToMany(\Account::class)->withPivot('is_owner');
    }

    public function pockets()
    {
        return $this->belongsToMany(\Pocket::class)->withPivot('is_owner');
    }

    public function transactions()
    {
        return $this->hasMany(\Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function paidByAccount()
    {
        return $this
            ->hasMany(\Transaction::class)
            ->where('wallet_type', \Account::MORPH_NAME)
            ->where('amount', '<', 0);
    }

    public function categories()
    {
        return $this->belongsToMany(\Category::class);
    }
}
