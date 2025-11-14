<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUniqueIdentifiers
{
    /**
     * Boot the trait.
     */
    protected static function bootGeneratesUniqueIdentifiers()
    {
        static::creating(function ($model) {
            if (empty($model->unique_user_id)) {
                $model->unique_user_id = self::generateUniqueUserId();
            }

            if (empty($model->account_number)) {
                $model->account_number = self::generateAccountNumber();
            }
        });
    }

    /**
     * Generate a unique user ID.
     *
     * @return string
     */
    protected static function generateUniqueUserId()
    {
        do {
            $uniqueId = 'USR' . strtoupper(Str::random(8));
        } while (self::where('unique_user_id', $uniqueId)->exists());

        return $uniqueId;
    }

    /**
     * Generate a unique 10-digit account number.
     *
     * @return string
     */
    protected static function generateAccountNumber()
    {
        do {
            $accountNumber = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }
}
