# Adiwarna API Documentation

## Table of Contents

1. [Overview](#overview)
2. [Base URL](#base-url)
3. [Response Format](#response-format)
4. [Error Handling](#error-handling)
5. [Pagination](#pagination)
6. [Rate Limiting](#rate-limiting)
7. [Endpoints by Module](#endpoints-by-module)

---

## Overview

Adiwarna API adalah RESTful API untuk sistem ERP PT Adiwarna Alam Raya.

**Total Endpoints:** 110 endpoints
**API Version:** v1
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Base URL

```
http://localhost:8000/api/v1
```

---

## Response Format

### Success Response

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Success with Pagination

```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

## Error Handling

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "message": "This action is unauthorized."
}
```

### 404 Not Found

```json
{
    "success": false,
    "message": "Resource not found"
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

---

## Pagination

All list endpoints support pagination:

**Query Parameters:**

-   `per_page`: Items per page (default: 15, max: 100)
-   `page`: Page number (default: 1)

**Example:**

```
GET /api/v1/customers?per_page=20&page=2
```

---

## Rate Limiting

-   General endpoints: 60 requests/minute
-   Login endpoint: 5 requests/minute

---

## Endpoints by Module

### 1. Authentication (3 endpoints)

-   `POST /auth/login` - Login
-   `POST /auth/logout` - Logout
-   `GET /auth/me` - Get current user

### 2. User Management (5 endpoints) - Admin Only

-   `GET /users` - List users
-   `POST /users` - Create user
-   `GET /users/{id}` - Get user
-   `PUT /users/{id}` - Update user
-   `DELETE /users/{id}` - Delete user

### 3. Customers (5 endpoints)

-   `GET /customers` - List customers
-   `POST /customers` - Create customer
-   `GET /customers/{id}` - Get customer
-   `PUT /customers/{id}` - Update customer
-   `DELETE /customers/{id}` - Delete customer

### 4. Employees (5 endpoints)

-   `GET /employees` - List employees
-   `POST /employees` - Create employee
-   `GET /employees/{id}` - Get employee
-   `PUT /employees/{id}` - Update employee
-   `DELETE /employees/{id}` - Delete employee

### 5. Quotations (5 endpoints)

-   `GET /quotations` - List quotations
-   `POST /quotations` - Create quotation with items
-   `GET /quotations/{id}` - Get quotation
-   `PUT /quotations/{id}` - Update quotation
-   `DELETE /quotations/{id}` - Delete quotation

### 6. Purchase Orders (5 endpoints)

-   `GET /purchase-orders` - List purchase orders
-   `POST /purchase-orders` - Create PO with items
-   `GET /purchase-orders/{id}` - Get PO
-   `PUT /purchase-orders/{id}` - Update PO
-   `DELETE /purchase-orders/{id}` - Delete PO

### 7. Work Assignments (5 endpoints)

-   `GET /work-assignments` - List work assignments
-   `POST /work-assignments` - Create with employees
-   `GET /work-assignments/{id}` - Get work assignment
-   `PUT /work-assignments/{id}` - Update work assignment
-   `DELETE /work-assignments/{id}` - Delete work assignment

### 8. Daily Activities (5 endpoints)

-   `GET /daily-activities` - List activities
-   `POST /daily-activities` - Create with members
-   `GET /daily-activities/{id}` - Get activity
-   `PUT /daily-activities/{id}` - Update activity
-   `DELETE /daily-activities/{id}` - Delete activity

### 9. Schedules (5 endpoints)

-   `GET /schedules` - List schedules
-   `POST /schedules` - Create schedule with items
-   `GET /schedules/{id}` - Get schedule
-   `PUT /schedules/{id}` - Update schedule
-   `DELETE /schedules/{id}` - Delete schedule

### 10. Work Orders (5 endpoints)

-   `GET /work-orders` - List work orders
-   `POST /work-orders` - Create with employees
-   `GET /work-orders/{id}` - Get work order
-   `PUT /work-orders/{id}` - Update work order
-   `DELETE /work-orders/{id}` - Delete work order

### 11. Document Transmittals (5 endpoints)

-   `GET /document-transmittals` - List transmittals
-   `POST /document-transmittals` - Create with documents
-   `GET /document-transmittals/{id}` - Get transmittal
-   `PUT /document-transmittals/{id}` - Update transmittal
-   `DELETE /document-transmittals/{id}` - Delete transmittal

### 12. Purchase Requisitions (5 endpoints)

-   `GET /purchase-requisitions` - List PRs
-   `POST /purchase-requisitions` - Create PR with items
-   `GET /purchase-requisitions/{id}` - Get PR
-   `PUT /purchase-requisitions/{id}` - Update PR
-   `DELETE /purchase-requisitions/{id}` - Delete PR

### 13. Material Receiving Reports (5 endpoints)

-   `GET /material-receiving-reports` - List MRRs
-   `POST /material-receiving-reports` - Create MRR with items
-   `GET /material-receiving-reports/{id}` - Get MRR
-   `PUT /material-receiving-reports/{id}` - Update MRR
-   `DELETE /material-receiving-reports/{id}` - Delete MRR

### 14. Equipment General (5 endpoints)

-   `GET /equipment/general` - List general equipment
-   `POST /equipment/general` - Create equipment
-   `GET /equipment/general/{id}` - Get equipment
-   `PUT /equipment/general/{id}` - Update equipment
-   `DELETE /equipment/general/{id}` - Delete equipment

### 15. Equipment Project (5 endpoints)

-   `GET /equipment/project` - List project equipment
-   `POST /equipment/project` - Create equipment
-   `GET /equipment/project/{id}` - Get equipment
-   `PUT /equipment/project/{id}` - Update equipment
-   `DELETE /equipment/project/{id}` - Delete equipment

### 16. Track Records (1 endpoint) - Read-only

-   `GET /track-records` - List work orders history

### 17. Operational (5 endpoints)

-   `GET /operational` - List operational records
-   `POST /operational` - Create operational
-   `GET /operational/{id}` - Get operational
-   `PUT /operational/{id}` - Update operational
-   `DELETE /operational/{id}` - Delete operational

### 18. About/Company Info (6 endpoints)

-   `GET /about` - List company info
-   `POST /about` - Create company info
-   `GET /about/active` - Get active company info
-   `GET /about/{id}` - Get company info
-   `PUT /about/{id}` - Update company info
-   `DELETE /about/{id}` - Delete company info

### 19. Payroll Projects (5 endpoints)

-   `GET /payroll/projects` - List projects
-   `POST /payroll/projects` - Create project
-   `GET /payroll/projects/{id}` - Get project
-   `PUT /payroll/projects/{id}` - Update project
-   `DELETE /payroll/projects/{id}` - Delete project

### 20. Payroll Periods (5 endpoints)

-   `GET /payroll/projects/{projectId}/periods` - List periods
-   `POST /payroll/projects/{projectId}/periods` - Create period
-   `GET /payroll/projects/{projectId}/periods/{id}` - Get period
-   `PUT /payroll/projects/{projectId}/periods/{id}` - Update period
-   `DELETE /payroll/projects/{projectId}/periods/{id}` - Delete period

### 21. Payroll Employees (6 endpoints)

-   `GET /payroll/periods/{periodId}/employees` - List employees
-   `POST /payroll/periods/{periodId}/employees` - Create employee
-   `GET /payroll/periods/{periodId}/employees/{id}` - Get employee
-   `PUT /payroll/periods/{periodId}/employees/{id}` - Update employee
-   `DELETE /payroll/periods/{periodId}/employees/{id}` - Delete employee
-   `POST /payroll/periods/{periodId}/employees/{id}/recalculate` - Recalculate

### 22. Payroll Timesheets (5 endpoints)

-   `GET /payroll/employees/{employeeId}/timesheets` - List timesheets
-   `POST /payroll/employees/{employeeId}/timesheets` - Create timesheet
-   `PUT /payroll/employees/{employeeId}/timesheets/{date}` - Update timesheet
-   `DELETE /payroll/employees/{employeeId}/timesheets/{date}` - Delete timesheet
-   `POST /payroll/employees/{employeeId}/timesheets/bulk` - Bulk update

### 23. Payroll Slips (4 endpoints)

-   `POST /payroll/periods/{periodId}/slips/generate` - Generate slips
-   `GET /payroll/periods/{periodId}/slips` - List slips
-   `GET /payroll/slips/{id}` - Get slip
-   `DELETE /payroll/slips/{id}` - Delete slip

---

**Total: 110 Endpoints**

For detailed request/response examples for each endpoint, see individual module documentation.

---

## Quick Start

1. Login to get token
2. Use token in Authorization header
3. Make requests to endpoints
4. Handle responses and errors

## Support

For issues or questions, contact development team.
