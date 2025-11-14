<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'beneficiary_name',
        'account_number',
        'bank_name',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the beneficiary.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the beneficiary.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
