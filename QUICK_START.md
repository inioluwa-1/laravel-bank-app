# Quick Start Guide - Laravel Bank App API

## Prerequisites
- PHP >= 8.2
- Composer
- SQLite/MySQL database
- Postman or similar API testing tool

## Setup Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Create Storage Link
```bash
php artisan storage:link
```

### 5. Start Development Server
```bash
php artisan serve
```

Server will start at: `http://localhost:8000`

---

## Quick Test Flow

### Step 1: Register a User

**POST** `http://localhost:8000/api/auth/register`

**Body (JSON):**
```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "account_type": "savings"
}
```

**Response:** Save the `token` from the response!

---

### Step 2: Create Transaction PIN

**POST** `http://localhost:8000/api/user/transaction-pin`

**Headers:**
```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Body:**
```json
{
    "transaction_pin": "1234",
    "transaction_pin_confirmation": "1234"
}
```

---

### Step 3: Deposit Money

**POST** `http://localhost:8000/api/transactions/deposit`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Body:**
```json
{
    "amount": 10000,
    "sender_name": "Initial Deposit"
}
```

---

### Step 4: Add a Beneficiary

**POST** `http://localhost:8000/api/beneficiaries`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Body:**
```json
{
    "beneficiary_name": "Jane Smith",
    "account_number": "9876543210",
    "bank_name": "ABC Bank",
    "amount": 500
}
```

---

### Step 5: Transfer Money

**POST** `http://localhost:8000/api/transactions/transfer`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Body:**
```json
{
    "amount": 500,
    "beneficiary_account_number": "9876543210",
    "beneficiary_name": "Jane Smith",
    "transaction_pin": "1234"
}
```

---

### Step 6: View Dashboard

**GET** `http://localhost:8000/api/dashboard/{userId}`

**Headers:**
```
Authorization: Bearer {your-token}
```

Replace `{userId}` with your user ID from registration response.

---

### Step 7: View Transaction History

**GET** `http://localhost:8000/api/transactions`

**Headers:**
```
Authorization: Bearer {your-token}
```

---

## Testing with cURL

### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123","account_type":"savings"}'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

### Get User Details
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Postman Collection

Import this JSON into Postman for quick testing:

### Environment Variables
Create these in Postman:
- `base_url`: `http://localhost:8000/api`
- `token`: (will be set automatically after login)

### Collection Structure
```
Laravel Bank App
├── Auth
│   ├── Register
│   ├── Login
│   └── Logout
├── User
│   ├── Get User
│   ├── Update Profile
│   ├── Upload Profile Picture
│   ├── Create Transaction PIN
│   ├── Update Transaction PIN
│   └── Add Next of Kin
├── Beneficiaries
│   ├── List Beneficiaries
│   ├── Add Beneficiary
│   ├── Update Beneficiary
│   └── Delete Beneficiary
├── Transactions
│   ├── Deposit
│   ├── Transfer
│   ├── Get Transaction History
│   └── Get Single Transaction
└── Dashboard
    └── Get Dashboard
```

---

## Common Issues & Solutions

### 1. "Unauthenticated" Error
**Solution:** Make sure you're including the Bearer token in the Authorization header.

### 2. "Transaction PIN is required"
**Solution:** Create a transaction PIN first using `/api/user/transaction-pin` endpoint.

### 3. "Insufficient funds"
**Solution:** Deposit money first using `/api/transactions/deposit` endpoint.

### 4. CORS Errors
**Solution:** Add CORS configuration in `config/cors.php` if testing from a frontend application.

### 5. Storage Link Not Working
**Solution:** Run `php artisan storage:link` to create symbolic link.

---

## Sample Test Data

### User 1
```json
{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "password": "password123",
    "account_type": "savings"
}
```

### User 2
```json
{
    "name": "Bob Smith",
    "email": "bob@example.com",
    "password": "password123",
    "account_type": "current"
}
```

### Beneficiary
```json
{
    "beneficiary_name": "Charity Organization",
    "account_number": "5555666677",
    "bank_name": "Community Bank",
    "amount": 100
}
```

---

## Database Seeding (Optional)

Create test data quickly:

```bash
php artisan tinker
```

Then run:
```php
// Create a user
$user = \App\Models\User::factory()->create([
    'email' => 'demo@example.com',
    'password' => bcrypt('password123')
]);

// Set transaction PIN
$user->update(['transaction_pin' => bcrypt('1234')]);

// Add balance
$user->update(['balance' => 10000]);

// Create beneficiaries
$user->beneficiaries()->createMany([
    ['beneficiary_name' => 'Alice', 'account_number' => '1111111111', 'bank_name' => 'Bank A', 'amount' => 100],
    ['beneficiary_name' => 'Bob', 'account_number' => '2222222222', 'bank_name' => 'Bank B', 'amount' => 200],
]);

echo "User created with email: demo@example.com, password: password123, PIN: 1234";
```

---

## API Response Formats

### Success Response
```json
{
    "message": "Operation successful",
    "data": { /* response data */ }
}
```

### Error Response
```json
{
    "error": "Error message",
    "details": "Additional details if available"
}
```

### Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

---

## Next Steps

1. ✅ Test all endpoints using Postman
2. ✅ Build a frontend (React, Vue, or mobile app)
3. ✅ Add email notifications for transactions
4. ✅ Implement rate limiting
5. ✅ Add two-factor authentication
6. ✅ Create admin dashboard
7. ✅ Add transaction receipts (PDF generation)
8. ✅ Implement account statements
9. ✅ Add webhooks for external integrations
10. ✅ Deploy to production

---

## Support

For issues or questions:
- Check the full API documentation in `API_DOCUMENTATION.md`
- Check the database schema in `DATABASE_SETUP.md`
- Review the source code in `app/Http/Controllers/Api/`

Happy coding! 🚀
