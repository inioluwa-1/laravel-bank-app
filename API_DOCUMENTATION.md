# Laravel Bank App - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token-here}
```

---

## 📋 Table of Contents
1. [Authentication](#authentication-endpoints)
2. [User Management](#user-management)
3. [Beneficiaries](#beneficiaries)
4. [Transactions](#transactions)
5. [Dashboard](#dashboard)

---

## Authentication Endpoints

### 1. Register User
**POST** `/api/auth/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "account_type": "savings"
}
```

**Validation Rules:**
- `name`: required, string, max 255
- `email`: required, email, unique
- `password`: required, min 8 characters, confirmed
- `account_type`: required, one of: savings, current, fixed

**Response (201):**
```json
{
    "message": "Registration successful",
    "user": {
        "id": 1,
        "unique_user_id": "USRABC12345",
        "name": "John Doe",
        "email": "john@example.com",
        "account_number": "1234567890",
        "account_type": "savings",
        "balance": "0.00",
        "has_transaction_pin": false,
        "profile_picture": null,
        "created_at": "2025-11-13 12:00:00"
    },
    "token": "1|abc123xyz..."
}
```

---

### 2. Login
**POST** `/api/auth/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "message": "Login successful",
    "user": { /* user object */ },
    "token": "2|xyz789abc..."
}
```

**Error Response (422):**
```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

---

### 3. Logout
**POST** `/api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "message": "Logout successful"
}
```

---

## User Management

### 4. Get User Details
**GET** `/api/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "unique_user_id": "USRABC12345",
        "name": "John Doe",
        "email": "john@example.com",
        "account_number": "1234567890",
        "account_type": "savings",
        "balance": "5000.00",
        "has_transaction_pin": true,
        "profile_picture": "profile_pictures/abc123.jpg",
        "created_at": "2025-11-13 12:00:00",
        "next_of_kin": {
            "id": 1,
            "name": "Jane Doe",
            "relationship": "Sister",
            "phone": "+1234567890",
            "email": "jane@example.com",
            "address": "123 Main St"
        }
    }
}
```

---

### 5. Update Profile
**PUT** `/api/user/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "John Updated",
    "email": "johnupdated@example.com",
    "account_type": "current"
}
```

**Note:** All fields are optional. Only send fields you want to update.

**Response (200):**
```json
{
    "message": "Profile updated successfully",
    "user": { /* updated user object */ }
}
```

---

### 6. Upload Profile Picture
**POST** `/api/user/profile-picture`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
profile_picture: [file] (jpeg, png, jpg, max 2MB)
```

**Response (200):**
```json
{
    "message": "Profile picture uploaded successfully",
    "profile_picture_url": "/storage/profile_pictures/abc123.jpg"
}
```

---

### 7. Create Transaction PIN
**POST** `/api/user/transaction-pin`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "transaction_pin": "1234",
    "transaction_pin_confirmation": "1234"
}
```

**Validation:**
- PIN must be exactly 4 digits
- PIN and confirmation must match

**Response (200):**
```json
{
    "message": "Transaction PIN created successfully"
}
```

**Error Response (400):**
```json
{
    "error": "Transaction PIN already exists. Use update endpoint to change it."
}
```

---

### 8. Update Transaction PIN
**PUT** `/api/user/transaction-pin`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "current_pin": "1234",
    "transaction_pin": "5678",
    "transaction_pin_confirmation": "5678"
}
```

**Response (200):**
```json
{
    "message": "Transaction PIN updated successfully"
}
```

**Error Response (401):**
```json
{
    "error": "Current transaction PIN is incorrect."
}
```

---

### 9. Add Next of Kin
**POST** `/api/user/next-of-kin`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "Jane Doe",
    "relationship": "Sister",
    "phone": "+1234567890",
    "email": "jane@example.com",
    "address": "123 Main Street, City, Country"
}
```

**Response (200):**
```json
{
    "message": "Next of kin details saved successfully",
    "next_of_kin": { /* next of kin object */ }
}
```

---

## Beneficiaries

### 10. List All Beneficiaries
**GET** `/api/beneficiaries`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "beneficiaries": [
        {
            "id": 1,
            "beneficiary_name": "Alice Smith",
            "account_number": "9876543210",
            "bank_name": "ABC Bank",
            "amount": "100.00",
            "created_at": "2025-11-13 12:00:00"
        }
    ]
}
```

---

### 11. Add Beneficiary
**POST** `/api/beneficiaries`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "beneficiary_name": "Alice Smith",
    "account_number": "9876543210",
    "bank_name": "ABC Bank",
    "amount": 100.00
}
```

**Validation:**
- `beneficiary_name`: required, string
- `account_number`: required, exactly 10 digits
- `bank_name`: required, string
- `amount`: optional, numeric, min 0

**Response (201):**
```json
{
    "message": "Beneficiary added successfully",
    "beneficiary": { /* beneficiary object */ }
}
```

---

### 12. Update Beneficiary
**PUT** `/api/beneficiaries/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "beneficiary_name": "Alice Updated",
    "account_number": "9876543210",
    "bank_name": "XYZ Bank",
    "amount": 200.00
}
```

**Response (200):**
```json
{
    "message": "Beneficiary updated successfully",
    "beneficiary": { /* updated beneficiary */ }
}
```

---

### 13. Delete Beneficiary
**DELETE** `/api/beneficiaries/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "message": "Beneficiary deleted successfully"
}
```

---

## Transactions

### 14. Deposit Funds
**POST** `/api/transactions/deposit`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "amount": 1000.00,
    "sender_account_number": "1111222233",
    "sender_name": "External Source"
}
```

**Validation:**
- `amount`: required, numeric, min 1
- `sender_account_number`: optional, 10 digits
- `sender_name`: optional, string

**Response (201):**
```json
{
    "message": "Deposit successful",
    "transaction": {
        "id": 1,
        "transaction_id": "TXN20251113ABC12345",
        "type": "deposit",
        "amount": "1000.00",
        "sender_account_number": "1111222233",
        "sender_name": "External Source",
        "beneficiary_account_number": "1234567890",
        "beneficiary_name": "John Doe",
        "status": "completed",
        "created_at": "2025-11-13 12:00:00"
    },
    "new_balance": "6000.00"
}
```

---

### 15. Transfer Funds
**POST** `/api/transactions/transfer`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "amount": 500.00,
    "beneficiary_account_number": "9876543210",
    "beneficiary_name": "Alice Smith",
    "beneficiary_id": 1,
    "transaction_pin": "1234"
}
```

**Validation:**
- `amount`: required, numeric, min 1
- `beneficiary_account_number`: required, 10 digits
- `beneficiary_name`: optional, string
- `beneficiary_id`: optional, exists in beneficiaries
- `transaction_pin`: required, 4 digits

**Response (201):**
```json
{
    "message": "Transfer successful",
    "transaction": { /* transaction object */ },
    "new_balance": "5500.00"
}
```

**Error Responses:**

**Insufficient Funds (400):**
```json
{
    "error": "Insufficient funds"
}
```

**Invalid PIN (401):**
```json
{
    "error": "Invalid transaction PIN"
}
```

**Self Transfer (400):**
```json
{
    "error": "Cannot transfer to your own account"
}
```

---

### 16. Get Transaction History
**GET** `/api/transactions`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `type`: filter by transaction type (deposit, transfer, withdrawal)
- `status`: filter by status (pending, completed, failed)
- `from_date`: filter from date (YYYY-MM-DD)
- `to_date`: filter to date (YYYY-MM-DD)
- `per_page`: items per page (default: 15)

**Example:**
```
GET /api/transactions?type=transfer&status=completed&per_page=20
```

**Response (200):**
```json
{
    "transactions": [
        { /* transaction object */ },
        { /* transaction object */ }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    }
}
```

---

### 17. Get Single Transaction
**GET** `/api/transactions/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "transaction": {
        "id": 1,
        "transaction_id": "TXN20251113ABC12345",
        "type": "transfer",
        "amount": "500.00",
        "beneficiary_account_number": "9876543210",
        "beneficiary_name": "Alice Smith",
        "sender_account_number": "1234567890",
        "sender_name": "John Doe",
        "status": "completed",
        "created_at": "2025-11-13 12:00:00",
        "beneficiary": {
            "id": 1,
            "beneficiary_name": "Alice Smith",
            "account_number": "9876543210",
            "bank_name": "ABC Bank"
        }
    }
}
```

---

## Dashboard

### 18. Get Dashboard Data
**GET** `/api/dashboard/{userId}`

**Headers:**
```
Authorization: Bearer {token}
```

**Note:** Users can only access their own dashboard.

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "account_number": "1234567890",
        "account_type": "savings",
        "balance": "5500.00",
        "profile_picture": "profile_pictures/abc123.jpg"
    },
    "statistics": {
        "total_deposits": "10000.00",
        "total_transfers": "4500.00",
        "total_transactions": 25,
        "beneficiaries_count": 5,
        "monthly_deposits": "2000.00",
        "monthly_transfers": "1500.00"
    },
    "recent_transactions": [
        { /* transaction 1 */ },
        { /* transaction 2 */ },
        { /* transaction 3 */ },
        { /* transaction 4 */ },
        { /* transaction 5 */ }
    ]
}
```

**Error Response (403):**
```json
{
    "error": "Unauthorized access"
}
```

---

## Error Handling

### Standard Error Responses

**Validation Error (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message 1",
            "Error message 2"
        ]
    }
}
```

**Unauthenticated (401):**
```json
{
    "message": "Unauthenticated."
}
```

**Not Found (404):**
```json
{
    "message": "Not found."
}
```

**Server Error (500):**
```json
{
    "error": "An error occurred. Please try again."
}
```

---

## Testing with Postman/cURL

### Example cURL Requests

**Register:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "account_type": "savings"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Get User (with token):**
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Transfer:**
```bash
curl -X POST http://localhost:8000/api/transactions/transfer \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 500,
    "beneficiary_account_number": "9876543210",
    "beneficiary_name": "Alice Smith",
    "transaction_pin": "1234"
  }'
```

---

## Running the Application

1. **Start the development server:**
```bash
php artisan serve
```

2. **Create storage link (for profile pictures):**
```bash
php artisan storage:link
```

3. **Run migrations (if not done):**
```bash
php artisan migrate
```

---

## Notes

- All monetary amounts are returned as strings with 2 decimal places
- Dates are in `Y-m-d H:i:s` format
- Transaction IDs are auto-generated in format: `TXN{date}{random}`
- Account numbers are auto-generated as 10-digit numbers
- Unique user IDs are in format: `USR{random8chars}`
- All balance updates use database transactions for consistency
- Transaction PIN must be set before transfers can be made
- Profile pictures are stored in `storage/app/public/profile_pictures`

---

## Security Features

✅ Password hashing with bcrypt
✅ Transaction PIN encryption
✅ Token-based authentication (Sanctum)
✅ Request validation
✅ Database transactions for financial operations
✅ Authorization checks (users can only access their own data)
✅ CSRF protection
✅ SQL injection protection (Eloquent ORM)
