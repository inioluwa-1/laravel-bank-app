<?php

namespace App\Models;

use App\Traits\GeneratesTransactionId;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use GeneratesTransactionId;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'user_id',
        'type',
        'amount',
        'beneficiary_id',
        'beneficiary_account_number',
        'beneficiary_name',
        'sender_account_number',
        'sender_name',
        'status',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the beneficiary associated with the transaction.
     */
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
