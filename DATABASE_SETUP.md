# Laravel Bank App - Database Setup Guide

## Overview
This guide explains the database schema and how to use it for your banking application.

## Database Tables

### 1. Users Table
Stores user account information including banking details.

**Fields:**
- `id` - Primary key
- `unique_user_id` - Auto-generated unique identifier (e.g., USR12345678)
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `account_number` - Auto-generated 10-digit account number
- `account_type` - Enum: savings/current/fixed (default: savings)
- `balance` - Decimal(15,2), default 0.00
- `transaction_pin` - Nullable, encrypted 4-digit PIN
- `profile_picture` - Nullable, path to profile image
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- Has one NextOfKin
- Has many Beneficiaries
- Has many Transactions

### 2. Next_of_Kin Table
Stores next of kin information for each user.

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `name` - Next of kin's name
- `relationship` - Relationship to user
- `phone` - Contact phone number
- `email` - Contact email
- `address` - Full address
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- Belongs to User

### 3. Beneficiaries Table
Stores saved beneficiaries for quick transfers.

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `beneficiary_name` - Name of beneficiary
- `account_number` - Beneficiary's account number
- `bank_name` - Name of bank
- `amount` - Default transfer amount (decimal 15,2)
- `created_at`, `updated_at` - Timestamps

**Relationships:**
- Belongs to User
- Has many Transactions

### 4. Transactions Table
Records all financial transactions.

**Fields:**
- `id` - Primary key
- `transaction_id` - Auto-generated unique ID (e.g., TXN20251113ABCD1234)
- `user_id` - Foreign key to users table
- `type` - Enum: deposit/transfer/withdrawal
- `amount` - Transaction amount (decimal 15,2)
- `beneficiary_id` - Nullable foreign key to beneficiaries
- `beneficiary_account_number` - Account number of recipient
- `beneficiary_name` - Name of recipient
- `sender_account_number` - Account number of sender
- `sender_name` - Name of sender
- `status` - Enum: pending/completed/failed
- `created_at` - Timestamp (no updated_at)

**Relationships:**
- Belongs to User
- Belongs to Beneficiary (nullable)

## Auto-Generated Fields

The following fields are automatically generated when creating records:

### User Model
- `unique_user_id` - Generated format: USR + 8 random uppercase characters
- `account_number` - Generated format: 10 random digits

### Transaction Model
- `transaction_id` - Generated format: TXN + YYYYMMDD + 8 random uppercase characters

## Usage Examples

### Creating a User
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'account_type' => 'savings',
    'balance' => 5000.00,
    'transaction_pin' => Hash::make('1234'),
]);

// unique_user_id and account_number are auto-generated
echo $user->unique_user_id; // e.g., USRABC12345
echo $user->account_number; // e.g., 1234567890
```

### Adding Next of Kin
```php
$user->nextOfKin()->create([
    'name' => 'Jane Doe',
    'relationship' => 'Sister',
    'phone' => '+1234567890',
    'email' => 'jane@example.com',
    'address' => '123 Main St, City, Country',
]);
```

### Adding a Beneficiary
```php
$beneficiary = $user->beneficiaries()->create([
    'beneficiary_name' => 'Alice Smith',
    'account_number' => '9876543210',
    'bank_name' => 'ABC Bank',
    'amount' => 100.00,
]);
```

### Creating a Transaction
```php
use App\Models\Transaction;

$transaction = Transaction::create([
    'user_id' => $user->id,
    'type' => 'transfer',
    'amount' => 500.00,
    'beneficiary_id' => $beneficiary->id,
    'beneficiary_account_number' => '9876543210',
    'beneficiary_name' => 'Alice Smith',
    'sender_account_number' => $user->account_number,
    'sender_name' => $user->name,
    'status' => 'completed',
    'created_at' => now(),
]);

// transaction_id is auto-generated
echo $transaction->transaction_id; // e.g., TXN20251113XYZABC12
```

### Retrieving Related Data
```php
// Get user's transactions
$transactions = $user->transactions;

// Get user's beneficiaries
$beneficiaries = $user->beneficiaries;

// Get user's next of kin
$nextOfKin = $user->nextOfKin;

// Get transaction with beneficiary details
$transaction = Transaction::with('beneficiary', 'user')->find(1);
```

### Querying Transactions
```php
// Get all deposits for a user
$deposits = $user->transactions()
    ->where('type', 'deposit')
    ->where('status', 'completed')
    ->orderBy('created_at', 'desc')
    ->get();

// Get total balance from completed deposits
$totalDeposits = $user->transactions()
    ->where('type', 'deposit')
    ->where('status', 'completed')
    ->sum('amount');
```

## Database Commands

### Run Migrations
```bash
php artisan migrate
```

### Rollback Last Migration
```bash
php artisan migrate:rollback
```

### Reset Database and Re-run Migrations
```bash
php artisan migrate:fresh
```

### Create Test Data (using factory)
```bash
php artisan tinker
```

Then in tinker:
```php
// Create a user with related data
$user = \App\Models\User::factory()->create();

// Create multiple users
\App\Models\User::factory(10)->create();
```

## Security Notes

1. **Password & Transaction PIN**: Always hash using `Hash::make()` before storing
2. **Transaction PIN**: Stored encrypted, validate using `Hash::check()`
3. **Balance Updates**: Use database transactions to ensure data integrity
4. **Account Numbers**: Auto-generated and unique
5. **Transaction IDs**: Auto-generated with date prefix for easy tracking

## Next Steps

1. Create controllers for each model
2. Implement authentication and authorization
3. Add validation rules for each model
4. Create API routes or web forms
5. Implement balance update logic with database transactions
6. Add email notifications for transactions
7. Implement transaction PIN verification
8. Add profile picture upload functionality

## Example Controller Logic for Transfer

```php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

public function transfer(Request $request)
{
    $request->validate([
        'beneficiary_account' => 'required|string',
        'amount' => 'required|numeric|min:1',
        'transaction_pin' => 'required|string',
    ]);

    $user = auth()->user();

    // Verify transaction PIN
    if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
        return response()->json(['error' => 'Invalid transaction PIN'], 401);
    }

    // Check sufficient balance
    if ($user->balance < $request->amount) {
        return response()->json(['error' => 'Insufficient funds'], 400);
    }

    DB::beginTransaction();
    try {
        // Deduct from sender
        $user->decrement('balance', $request->amount);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'transfer',
            'amount' => $request->amount,
            'beneficiary_account_number' => $request->beneficiary_account,
            'beneficiary_name' => $request->beneficiary_name,
            'sender_account_number' => $user->account_number,
            'sender_name' => $user->name,
            'status' => 'completed',
            'created_at' => now(),
        ]);

        DB::commit();
        return response()->json([
            'message' => 'Transfer successful',
            'transaction' => $transaction
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Transfer failed'], 500);
    }
}
```
