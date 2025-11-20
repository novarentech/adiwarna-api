# Postman Collections untuk Adiwarna API

Dokumentasi ini berisi informasi tentang Postman collections yang tersedia untuk testing Adiwarna API.

## Collections yang Tersedia
1. **Authentication** - Login, Logout, Get Current User
2. **User Management** - CRUD Users dengan role-based access
3. **Customers** - CRUD Customers dengan locations
4. **Employees** - CRUD Employees
5. **Quotations** - CRUD Quotations dengan items, adiwarnas, clients
6. **Purchase Orders** - CRUD Purchase Orders dengan items
7. **Work Assignments** - CRUD Work Assignments dengan employees
8. **Daily Activities** - CRUD Daily Activities dengan members dan descriptions
9. **Schedules** - CRUD Schedules dengan items
10. **Work Orders** - CRUD Work Orders dengan employees
11. **Document Transmittals** - CRUD Document Transmittals dengan documents
12. **Purchase Requisitions** - CRUD Purchase Requisitions dengan items
13. **Material Receiving Reports** - CRUD Material Receiving Reports dengan items 
14. **Equipment General** - CRUD General Equipment management 
15. **Equipment Project** - CRUD Project Equipment management 
16. **Track Records** - CRUD Track Records untuk project history 
17. **Operational** - CRUD Operational data management 
18. **About/Company Info** - CRUD Company information dengan active status
19. **Payroll Projects** - CRUD Payroll Projects 
20. **Payroll Periods** - CRUD Payroll Periods (nested under projects) 
21. **Payroll Employees** - CRUD Payroll Employees (nested under periods) dengan recalculate 
22. **Payroll Timesheets** - CRUD Payroll Timesheets (nested under employees) dengan bulk update 
23. **Payroll Slips** - Generate dan manage Payroll Slips (nested under periods)

## Cara Menggunakan

### Setup Awal

1. Import semua collection ke Postman
2. Set environment variables:
    - `base_url`: URL base API (default: http://localhost:8000/api/v1)
    - `token`: Token autentikasi (akan di-set otomatis setelah login)
    - `project_id`: ID Payroll Project (untuk Part 3)
    - `period_id`: ID Payroll Period (untuk Part 3)
    - `payroll_employee_id`: ID Payroll Employee (untuk Part 3)

### Workflow

1. **Login**: Jalankan request "Login" dari Collection 1 untuk mendapatkan token
2. Token akan otomatis tersimpan di environment variable `token`
3. Semua request selanjutnya akan menggunakan token ini secara otomatis
4. Untuk modul Payroll, pastikan sudah membuat Project dan Period terlebih dahulu

### Nested Resources (Payroll Module)

Modul Payroll menggunakan nested resources:

-   **Periods** nested under **Projects**: `/payroll/projects/{projectId}/periods`
-   **Employees** nested under **Periods**: `/payroll/periods/{periodId}/employees`
-   **Timesheets** nested under **Employees**: `/payroll/employees/{employeeId}/timesheets`
-   **Slips** nested under **Periods**: `/payroll/periods/{periodId}/slips`

## Query Parameters

Setiap endpoint list memiliki query parameters standar dengan description lengkap:

-   `per_page`: Jumlah item per halaman (default: 15, max: 100)
-   `page`: Nomor halaman untuk pagination (default: 1)
-   `search`: Pencarian berdasarkan field tertentu (jika tersedia)
-   Filter spesifik per modul:
    -   `customer_id`: Filter by customer
    -   `usertype`: Filter by user role (admin, teknisi, user)
    -   Dan lain-lain sesuai modul

## Fitur Khusus

### Auto-calculation

Beberapa endpoint memiliki fitur auto-calculation:

-   **Quotations**: Total amount dihitung otomatis dari items
-   **Purchase Orders**: Total amount dihitung otomatis dari items
-   **Purchase Requisitions**: Total amount dihitung otomatis dari items
-   **Payroll Employees**: Total salary dihitung otomatis dari base_salary + allowances - deductions + overtime

### Bulk Operations

-   **Payroll Timesheets**: Mendukung bulk update untuk multiple dates sekaligus

### Special Endpoints

-   **Payroll Employee Recalculate**: POST endpoint untuk recalculate total salary
-   **Payroll Slips Generate**: POST endpoint untuk generate slips untuk semua employees dalam period
-   **About Active**: GET endpoint untuk mendapatkan company info yang aktif

## Response Format

Semua response menggunakan format standar:

```json
{
    "success": true,
    "message": "Success message",
    "data": {
        // Response data
    }
}
```

Untuk list/pagination:

```json
{
    "success": true,
    "message": "Success message",
    "data": {
        "data": [...],
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7
    }
}
```

## Catatan Penting

-   Semua endpoint (kecuali login) memerlukan autentikasi menggunakan Bearer Token
-   Token akan otomatis di-set setelah login berhasil melalui test script
-   Setiap query parameter memiliki description yang menjelaskan fungsinya
-   Setiap request body memiliki contoh data yang valid
-   Variable path (`:id`, `:projectId`, dll) sudah di-set dengan default value
-   Untuk testing, gunakan Collection 1 terlebih dahulu untuk setup data master (Users, Customers, Employees)
-   Kemudian lanjutkan ke Collection 2 dan 3 untuk modul-modul lainnya

## Troubleshooting

### Token Expired

Jika mendapat error 401 Unauthorized:

1. Jalankan ulang request "Login"
2. Token baru akan otomatis tersimpan

### Nested Resource Not Found

Jika mendapat error 404 pada nested resources:

1. Pastikan parent resource sudah dibuat (Project untuk Period, Period untuk Employee, dll)
2. Update variable `project_id`, `period_id`, atau `payroll_employee_id` dengan ID yang valid

### Validation Error

Jika mendapat error 422 Validation Error:

1. Periksa request body sesuai dengan contoh yang diberikan
2. Pastikan semua required fields sudah diisi
3. Periksa format data (date format: YYYY-MM-DD, dll)

## Summary

Total 23 modul API telah didokumentasikan dalam 3 Postman collections:

-   **Collection 1**: 12 modul inti (Authentication sampai Purchase Requisitions)
-   **Collection 2**: 6 modul pendukung (Material Receiving Reports sampai About)
-   **Collection 3**: 5 modul Payroll (Projects, Periods, Employees, Timesheets, Slips)

Semua endpoint sudah dilengkapi dengan:
✅ Description lengkap untuk setiap request
✅ Query parameters dengan description
✅ Path variables dengan description
✅ Request body examples yang valid
✅ Auto-save token setelah login
✅ Bearer token authentication untuk semua protected endpoints
