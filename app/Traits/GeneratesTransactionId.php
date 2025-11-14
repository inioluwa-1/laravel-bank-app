<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesTransactionId
{
    /**
     * Boot the trait.
     */
    protected static function bootGeneratesTransactionId()
    {
        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = self::generateTransactionId();
            }
        });
    }

    /**
     * Generate a unique transaction ID.
     *
     * @return string
     */
    protected static function generateTransactionId()
    {
        do {
            $transactionId = 'TXN' . date('Ymd') . strtoupper(Str::random(8));
        } while (self::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }
}
