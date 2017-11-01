<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Transaction
 *
 * @property int $id
 * @property int $parent_id
 * @property int $wallet_id
 * @property int $status_code
 * @property float $amount
 * @property string $wallet_type
 * @property string $comment
 * @property string $status
 *
 * @property-read \App\Models\User $user
 * @property-read null|\App\Models\Transaction $transaction
 * @property-read \App\Models\Account|\App\Models\Pocket $wallet
 *
 * @mixin  \Illuminate\Database\Eloquent\Model
 */

class Transaction extends Model
{
    const SUCCESS_STATUS = 1;
    const CANCELED_STATUS = 2;

    protected $fillable = [
        'user_id',
        'parent_id',
        'wallet_id',
        'wallet_type',
        'amount',
        'status',
        'comment'
    ];

    // SCOPES

    public function scopePaidByAccount(Builder $query)
    {
        return $query->where('wallet_type', \Account::MORPH_NAME);
    }

    // RELATIONS

    public function user()
    {
        return $this->belongsTo(\User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(\Transaction::class, 'parent_id');
    }

    public function wallet()
    {
        return $this->morphTo();
    }

    // ACCESSORS

    /**
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->status == self::SUCCESS_STATUS) {
            return 'Compelete';
        } elseif ($this->status == self::CANCELED_STATUS) {
            return 'Canceled';
        }

        return 'undefined';
    }

    /**
     * @return int
     */
    public function getStatusCodeAttribute()
    {
        return $this->status;
    }

    /**
     * @return float
     */
    public function getAmountNumberAttribute()
    {
        return abs($this->amount);
    }
}
