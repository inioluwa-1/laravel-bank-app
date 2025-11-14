<?php

namespace App\Helpers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class AccountHelper
{
    /**
     * Generate a unique 10-digit account number.
     *
     * @return string
     */
    public static function generateAccountNumber(): string
    {
        do {
            $accountNumber = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (User::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }

    /**
     * Generate a unique user ID in format: USR-YYYYMMDD-XXXX
     *
     * @return string
     */
    public static function generateUniqueUserId(): string
    {
        do {
            $date = date('Ymd');
            $random = strtoupper(Str::random(4));
            $uniqueId = "USR-{$date}-{$random}";
        } while (User::where('unique_user_id', $uniqueId)->exists());

        return $uniqueId;
    }

    /**
     * Generate a unique transaction ID in format: TXN-timestamp-random
     *
     * @return string
     */
    public static function generateTransactionId(): string
    {
        do {
            $timestamp = time();
            $random = strtoupper(Str::random(6));
            $transactionId = "TXN-{$timestamp}-{$random}";
        } while (Transaction::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }

    /**
     * Format amount to currency format.
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public static function formatAmount(float $amount, string $currency = 'NGN'): string
    {
        return $currency . ' ' . number_format($amount, 2, '.', ',');
    }

    /**
     * Check if account number is valid format.
     *
     * @param string $accountNumber
     * @return bool
     */
    public static function isValidAccountNumber(string $accountNumber): bool
    {
        return preg_match('/^\d{10}$/', $accountNumber);
    }

    /**
     * Mask account number for display (show only last 4 digits).
     *
     * @param string $accountNumber
     * @return string
     */
    public static function maskAccountNumber(string $accountNumber): string
    {
        if (strlen($accountNumber) !== 10) {
            return $accountNumber;
        }

        return '******' . substr($accountNumber, -4);
    }
}
