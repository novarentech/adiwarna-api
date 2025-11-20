# Adiwarna API - Laravel 12

API Backend untuk sistem ERP PT Adiwarna Alam Raya menggunakan Laravel 12 dengan arsitektur API-first.

## 📋 Table of Contents

-   [Overview](#overview)
-   [Technology Stack](#technology-stack)
-   [System Requirements](#system-requirements)
-   [Installation](#installation)
-   [Environment Configuration](#environment-configuration)
-   [Database Setup](#database-setup)
-   [API Authentication](#api-authentication)
-   [Running the Application](#running-the-application)
-   [API Documentation](#api-documentation)
-   [Project Structure](#project-structure)

---

## 🎯 Overview

Adiwarna API adalah backend system untuk mengelola operasional PT Adiwarna Alam Raya, meliputi:

-   **User Management** - Manajemen user dengan role-based access (Admin, Teknisi, User)
-   **Customer & Employee Management** - Master data pelanggan dan karyawan
-   **Sales Management** - Quotations dan Purchase Orders
-   **Operations Management** - Work Assignments, Daily Activities, Schedules, Work Orders
-   **Document Management** - Document Transmittals, Purchase Requisitions, Material Receiving Reports
-   **Equipment Management** - General Equipment dan Project Equipment
-   **Project Tracking** - Track Records dan Operational Records
-   **Payroll System** - Comprehensive payroll management dengan timesheet dan slip generation
-   **Company Information** - About/Company profile management

---

## 🛠 Technology Stack

-   **Framework**: Laravel 12.x
-   **PHP**: 8.2+
-   **Database**: MySQL 8.0+
-   **Authentication**: Laravel Sanctum (Token-based)
-   **Architecture**: Repository Pattern + Service Layer
-   **API**: RESTful API with JSON responses
-   **Validation**: Form Request Validation
-   **Authorization**: Policy-based Authorization

---

## 💻 System Requirements

-   PHP >= 8.2
-   Composer >= 2.0
-   MySQL >= 8.0 atau MariaDB >= 10.3
-   Node.js >= 18.x (untuk asset compilation, optional)
-   Git

---

## 📦 Installation

### 1. Clone Repository

```bash
git clone https://github.com/dzuura/adiwarna-api.git
cd adiwarna-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

## ⚙️ Environment Configuration

Edit file `.env` dan sesuaikan dengan environment Anda:

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adiwarna_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Application Configuration

```env
APP_NAME="Adiwarna API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Sanctum Configuration

```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DRIVER=cookie
```

---

## 🗄️ Database Setup

### 1. Create Database

Buat database MySQL:

```sql
CREATE DATABASE adiwarna_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Migrations

Jalankan semua migrations untuk membuat struktur database:

```bash
php artisan migrate
```

Migrations akan membuat tabel untuk:

-   Users & Authentication
-   Customers & Customer Locations
-   Employees
-   Quotations (dengan items, adiwarnas, clients)
-   Purchase Orders (dengan items)
-   Work Assignments & Daily Activities
-   Schedules & Work Orders
-   Document Transmittals
-   Purchase Requisitions & Material Receiving Reports
-   Equipment (General & Project)
-   Track Records & Operational Records
-   Payroll System (Projects, Periods, Employees, Timesheets, Slips)
-   Company Information (About)

### 3. Seed Database (Optional)

Untuk development, Anda bisa seed database dengan sample data:

```bash
php artisan db:seed
```

Ini akan membuat:

-   1 Admin user (admin@adiwarna.com / password)
-   2 Teknisi users
-   3 Regular users
-   10 Customers dengan locations
-   15 Employees
-   Sample data untuk semua modul

**Default Admin Credentials:**

-   Email: `admin@adiwarna.com`
-   Password: `password`

---

## 🔐 API Authentication

API menggunakan Laravel Sanctum untuk token-based authentication.

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@adiwarna.com",
  "password": "password"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@adiwarna.com",
            "usertype": "admin"
        },
        "token": "1|abc123..."
    }
}
```

### Using the Token

Gunakan token di header untuk semua protected endpoints:

```http
GET /api/v1/users
Authorization: Bearer 1|abc123...
```

### Logout

```http
POST /api/v1/auth/logout
Authorization: Bearer 1|abc123...
```

---

## 🚀 Running the Application

### Development Server

```bash
php artisan serve
```

API akan berjalan di `http://localhost:8000`

### Testing API

Gunakan tools seperti:

-   Postman
-   Insomnia
-   cURL
-   Thunder Client (VS Code extension)

---

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api/v1
```

### API Endpoints Overview

#### Authentication

-   `POST /auth/login` - Login
-   `POST /auth/logout` - Logout
-   `GET /auth/me` - Get current user

#### User Management (Admin Only)

-   `GET /users` - List users
-   `POST /users` - Create user
-   `GET /users/{id}` - Get user
-   `PUT /users/{id}` - Update user
-   `DELETE /users/{id}` - Delete user

#### Customers

-   `GET /customers` - List customers
-   `POST /customers` - Create customer
-   `GET /customers/{id}` - Get customer
-   `PUT /customers/{id}` - Update customer
-   `DELETE /customers/{id}` - Delete customer

#### Employees

-   `GET /employees` - List employees
-   `POST /employees` - Create employee
-   `GET /employees/{id}` - Get employee
-   `PUT /employees/{id}` - Update employee
-   `DELETE /employees/{id}` - Delete employee

#### Quotations

-   `GET /quotations` - List quotations
-   `POST /quotations` - Create quotation (with items)
-   `GET /quotations/{id}` - Get quotation
-   `PUT /quotations/{id}` - Update quotation
-   `DELETE /quotations/{id}` - Delete quotation

#### Purchase Orders

-   `GET /purchase-orders` - List purchase orders
-   `POST /purchase-orders` - Create PO (with items)
-   `GET /purchase-orders/{id}` - Get PO
-   `PUT /purchase-orders/{id}` - Update PO
-   `DELETE /purchase-orders/{id}` - Delete PO

#### Work Assignments

-   `GET /work-assignments` - List work assignments
-   `POST /work-assignments` - Create work assignment
-   `GET /work-assignments/{id}` - Get work assignment
-   `PUT /work-assignments/{id}` - Update work assignment
-   `DELETE /work-assignments/{id}` - Delete work assignment

#### Daily Activities

-   `GET /daily-activities` - List daily activities
-   `POST /daily-activities` - Create daily activity
-   `GET /daily-activities/{id}` - Get daily activity
-   `PUT /daily-activities/{id}` - Update daily activity
-   `DELETE /daily-activities/{id}` - Delete daily activity

#### Payroll System

-   `GET /payroll/projects` - List payroll projects
-   `GET /payroll/projects/{projectId}/periods` - List periods
-   `GET /payroll/periods/{periodId}/employees` - List employees
-   `GET /payroll/employees/{employeeId}/timesheets` - List timesheets
-   `POST /payroll/periods/{periodId}/slips/generate` - Generate slips

**Untuk dokumentasi lengkap, lihat [Postman Collection](https://documenter.getpostman.com/view/39730752/2sB3WyJFsd)**
---

## 📁 Project Structure

```
adiwarna-api/
├── app/
│   ├── Contracts/
│   │   └── Repositories/          # Repository interfaces
│   ├── Enums/                     # PHP Enums (UserType, Status, etc.)
│   ├── Http/
│   │   ├── Controllers/Api/V1/    # API Controllers
│   │   ├── Requests/Api/V1/       # Form Request Validation
│   │   └── Resources/V1/          # API Resources
│   ├── Models/                    # Eloquent Models
│   ├── Policies/                  # Authorization Policies
│   ├── Repositories/              # Repository Implementations
│   └── Services/                  # Business Logic Services
├── database/
│   ├── factories/                 # Model Factories
│   ├── migrations/                # Database Migrations
│   └── seeders/                   # Database Seeders
├── routes/
│   └── api.php                    # API Routes
├── docs/                          # Documentation
│   ├── postman/                   # Postman Collection
│   └── API.md                     # API Documentation
└── README.md
```

### Architecture Pattern

**Repository Pattern + Service Layer:**

```
Controller → Service → Repository → Model → Database
```

-   **Controllers**: Handle HTTP requests/responses
-   **Services**: Business logic dan transactions
-   **Repositories**: Data access layer
-   **Models**: Eloquent ORM models
-   **Policies**: Authorization logic

---

## 🔒 Authorization

System menggunakan role-based access control dengan 3 roles:

### Admin

-   Full access ke semua modul
-   User management
-   Semua CRUD operations

### Teknisi

-   View dan create Daily Activities (own only)
-   View Work Assignments
-   Limited access

### User

-   Minimal access
-   View only untuk assigned data

---

## 🧪 Testing

### Run Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

---

## 📝 Additional Commands

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Commands

```bash
# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

---

## 🤝 Contributing

1. Create feature branch
2. Make changes
3. Write tests
4. Submit pull request

---

## 📄 License

Proprietary - PT Adiwarna Alam Raya

---

## 📞 Support

Untuk pertanyaan atau issues, hubungi tim development.

---

## 🔄 Version History

### v1.0.0 (Current)

-   Initial release
-   Full CRUD untuk semua modul
-   Authentication dengan Sanctum
-   Role-based authorization
-   Comprehensive API documentation
