# 🏦 Laravel Bank Application

A comprehensive banking application API built with Laravel, featuring user management, transactions, beneficiaries, and dashboard analytics.

## ✨ Features

### 🔐 Authentication & Authorization
- User registration and login
- Token-based authentication (Laravel Sanctum)
- Secure logout with token revocation
- Transaction PIN for financial operations

### 👤 User Management
- Profile management
- Profile picture upload
- Transaction PIN creation and updates
- Next of kin information
- Account types (Savings, Current, Fixed)

### 💳 Beneficiary Management
- Add and manage beneficiaries
- Update beneficiary details
- Delete beneficiaries
- Default amount setting

### 💰 Transaction System
- Deposit funds
- Transfer to beneficiaries
- Transaction history with filters
- Internal and external transfers
- Real-time balance updates
- Transaction status tracking

### 📊 Dashboard Analytics
- Account balance overview
- Total deposits and transfers
- Monthly statistics
- Recent transactions
- Beneficiaries count

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.2
- Composer
- SQLite or MySQL

### Installation

1. **Clone and install dependencies**
```bash
composer install
```

2. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Run migrations**
```bash
php artisan migrate
```

4. **Create storage link**
```bash
php artisan storage:link
```

5. **Start development server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

## 📚 Documentation

### Complete Guides
- **[API Documentation](API_DOCUMENTATION.md)** - Complete API reference with examples
- **[Quick Start Guide](QUICK_START.md)** - Step-by-step testing guide
- **[Database Schema](DATABASE_SETUP.md)** - Database structure and usage
- **[Phase 2 Summary](PHASE_2_SUMMARY.md)** - Implementation details

### Postman Collection
Import `postman_collection.json` into Postman for instant testing with all endpoints pre-configured.

## 🔌 API Endpoints

### Authentication
```
POST   /api/auth/register     - Register new user
POST   /api/auth/login        - User login
POST   /api/auth/logout       - User logout
```

### User Management
```
GET    /api/user                      - Get user details
PUT    /api/user/profile              - Update profile
POST   /api/user/profile-picture      - Upload picture
POST   /api/user/transaction-pin      - Create PIN
PUT    /api/user/transaction-pin      - Update PIN
POST   /api/user/next-of-kin          - Add next of kin
```

### Beneficiaries
```
GET    /api/beneficiaries         - List all
POST   /api/beneficiaries         - Add new
PUT    /api/beneficiaries/{id}    - Update
DELETE /api/beneficiaries/{id}    - Delete
```

### Transactions
```
GET    /api/transactions          - Get history
GET    /api/transactions/{id}     - Get single
POST   /api/transactions/deposit  - Deposit funds
POST   /api/transactions/transfer - Transfer funds
```

### Dashboard
```
GET    /api/dashboard/{userId}    - Get dashboard data
```

**Total: 18 endpoints**

## 🧪 Testing

### Quick Test Flow

1. **Register a user**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123","account_type":"savings"}'
```

2. **Create transaction PIN**
```bash
curl -X POST http://localhost:8000/api/user/transaction-pin \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"transaction_pin":"1234","transaction_pin_confirmation":"1234"}'
```

3. **Deposit funds**
```bash
curl -X POST http://localhost:8000/api/transactions/deposit \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"amount":10000}'
```

See [QUICK_START.md](QUICK_START.md) for complete testing guide.

## 🗄️ Database Schema

### Users Table
- Auto-generated unique user ID
- Auto-generated 10-digit account number
- Balance tracking
- Transaction PIN (encrypted)
- Profile picture support

### Next of Kin Table
- One-to-one relationship with users
- Complete contact information

### Beneficiaries Table
- User-specific beneficiary list
- Default transfer amounts
- Bank details storage

### Transactions Table
- Auto-generated transaction IDs
- Type: deposit, transfer, withdrawal
- Status tracking
- Complete sender/recipient details

See [DATABASE_SETUP.md](DATABASE_SETUP.md) for complete schema.

## 🔒 Security Features

✅ Password hashing with bcrypt  
✅ Transaction PIN encryption  
✅ Token-based authentication  
✅ Request validation  
✅ Database transactions for financial operations  
✅ Authorization checks  
✅ CSRF protection  
✅ SQL injection prevention  

## 📦 Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── BeneficiaryController.php
│   │   ├── TransactionController.php
│   │   └── DashboardController.php
│   ├── Requests/
│   │   ├── RegisterRequest.php
│   │   ├── LoginRequest.php
│   │   ├── TransferRequest.php
│   │   └── ...
│   └── Resources/
│       ├── UserResource.php
│       ├── BeneficiaryResource.php
│       └── ...
├── Models/
│   ├── User.php
│   ├── NextOfKin.php
│   ├── Beneficiary.php
│   └── Transaction.php
└── Traits/
    ├── GeneratesUniqueIdentifiers.php
    └── GeneratesTransactionId.php
```

## 🎯 Features Highlights

### Auto-Generation
- Unique user IDs (USR12345678)
- Account numbers (10 digits)
- Transaction IDs (TXN20251113ABC12345)

### Smart Transactions
- Automatic balance updates
- Internal transfer detection
- Beneficiary linking
- Status tracking

### Flexible Filtering
- Filter by transaction type
- Filter by status
- Date range filtering
- Pagination support

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Sanctum
- **Database**: SQLite/MySQL
- **PHP**: 8.2+
- **API**: RESTful

## 📈 Statistics

- **18 API Endpoints**
- **5 Controllers**
- **8 Form Request Validators**
- **4 API Resources**
- **4 Models**
- **2 Custom Traits**

## 🤝 Contributing

This is a learning project. Feel free to fork and enhance!

## 📄 License

Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Support

- Check the documentation files for detailed information
- Review the Postman collection for API examples
- Examine controller code for implementation details

---

**Built with ❤️ using Laravel**
