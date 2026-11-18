# SM Sport Center - API Documentation

## Overview
REST API untuk SM Sport Center - Sistem Reservasi Lapangan Olahraga. 
Dokumentasi lengkap untuk integrasi dengan aplikasi mobile atau third-party.

## Base URL
```
Development: http://localhost:8000/api/v1
Production: https://smsport.example.com/api/v1
```

## Authentication
Menggunakan Laravel Sanctum Token Authentication.

### Get Token (Login)
```
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@email.com",
  "password": "password"
}

Response (200 OK):
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi",
      "email": "budi@email.com",
      "role": "customer"
    },
    "token": "1|abc123xyz..."
  }
}
```

### Headers untuk Request Authenticated
```
Authorization: Bearer <token>
Content-Type: application/json
Accept: application/json
```

---

## Endpoints

### Auth Endpoints

#### 1. Register
```
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "Nama Lengkap",
  "email": "email@example.com",
  "password": "password",
  "password_confirmation": "password",
  "phone": "08123456789",
  "address": "Alamat Lengkap"
}

Response (201 Created):
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {...},
    "token": "..."
  }
}
```

#### 2. Login
```
POST /api/v1/auth/login

Response: (lihat di atas)
```

#### 3. Logout
```
POST /api/v1/auth/logout
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "message": "Logout berhasil"
}
```

#### 4. Get Current User
```
GET /api/v1/auth/me
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Budi",
    "email": "budi@email.com",
    "phone": "08123456789",
    "role": "customer",
    "address": "Jl. Sudirman 1",
    "created_at": "2026-07-23T10:00:00Z"
  }
}
```

---

### Field Endpoints

#### 1. Get All Fields
```
GET /api/v1/fields?page=1&per_page=10&type=futsal&status=available
Authorization: Bearer <token>

Query Parameters:
  - page: Halaman (default: 1)
  - per_page: Item per halaman (default: 10)
  - type: Jenis (futsal, badminton) - Optional
  - status: Status (available, unavailable, maintenance) - Optional

Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Futsal 1",
      "type": "futsal",
      "capacity": 10,
      "price_per_hour": 150000,
      "status": "available",
      "description": "Lapangan futsal standar dengan fasilitas lengkap",
      "created_at": "2026-07-23T10:00:00Z"
    },
    ...
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 5,
    "last_page": 1
  }
}
```

#### 2. Get Field by ID
```
GET /api/v1/fields/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {...}
}
```

#### 3. Create Field (Admin Only)
```
POST /api/v1/fields
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Futsal 3",
  "type": "futsal",
  "capacity": 10,
  "price_per_hour": 150000,
  "status": "available",
  "description": "Deskripsi lapangan"
}

Response (201 Created):
{
  "success": true,
  "message": "Lapangan berhasil dibuat",
  "data": {...}
}
```

#### 4. Update Field (Admin Only)
```
PUT /api/v1/fields/{id}
Authorization: Bearer <token>

Body: (sama dengan create)

Response (200 OK):
{
  "success": true,
  "message": "Lapangan berhasil diupdate",
  "data": {...}
}
```

#### 5. Delete Field (Admin Only)
```
DELETE /api/v1/fields/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "message": "Lapangan berhasil dihapus"
}
```

---

### Reservation Endpoints

#### 1. Get All Reservations (Admin) / My Reservations (Customer)
```
GET /api/v1/reservations?page=1&status=pending
Authorization: Bearer <token>

Query Parameters:
  - page: Halaman
  - status: pending, confirmed, completed, cancelled
  - field_id: Filter by field
  - date_from: Start date (YYYY-MM-DD)
  - date_to: End date (YYYY-MM-DD)

Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "name": "Budi",
        "email": "budi@email.com"
      },
      "field": {
        "id": 1,
        "name": "Futsal 1"
      },
      "reservation_date": "2026-07-24",
      "start_time": "10:00",
      "end_time": "12:00",
      "duration_hours": 2,
      "total_price": 300000,
      "status": "pending",
      "notes": "Catatan reservasi",
      "created_at": "2026-07-23T10:00:00Z"
    },
    ...
  ],
  "pagination": {...}
}
```

#### 2. Get Reservation by ID
```
GET /api/v1/reservations/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {...}
}
```

#### 3. Create Reservation
```
POST /api/v1/reservations
Authorization: Bearer <token>
Content-Type: application/json

{
  "field_id": 1,
  "reservation_date": "2026-07-24",
  "start_time": "10:00",
  "end_time": "12:00",
  "notes": "Catatan opsional"
}

Response (201 Created):
{
  "success": true,
  "message": "Reservasi berhasil dibuat",
  "data": {...}
}

Error Response (409 Conflict):
{
  "success": false,
  "message": "Waktu reservasi bentrok dengan reservasi lain",
  "code": "BOOKING_CONFLICT"
}
```

#### 4. Update Reservation Status (Admin Only)
```
PUT /api/v1/reservations/{id}/status
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "confirmed"
}

Response (200 OK):
{
  "success": true,
  "message": "Status reservasi diupdate",
  "data": {...}
}
```

#### 5. Cancel Reservation
```
POST /api/v1/reservations/{id}/cancel
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "message": "Reservasi dibatalkan",
  "data": {...}
}
```

#### 6. Check Availability
```
GET /api/v1/reservations/check-availability
Authorization: Bearer <token>
Query: ?field_id=1&date=2026-07-24&start_time=10:00&end_time=12:00

Response (200 OK):
{
  "success": true,
  "available": true,
  "message": "Waktu tersedia untuk reservasi"
}
```

---

### Transaction Endpoints

#### 1. Get All Transactions (Admin) / My Transactions (Customer)
```
GET /api/v1/transactions?page=1&status=completed
Authorization: Bearer <token>

Query Parameters:
  - page: Halaman
  - status: pending, completed, failed, refunded
  - date_from: Start date
  - date_to: End date

Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "transaction_code": "TRX20260723001",
      "user": {
        "id": 2,
        "name": "Budi"
      },
      "reservation": {
        "id": 1,
        "field": {...}
      },
      "amount": 300000,
      "payment_method": "transfer",
      "status": "completed",
      "payment_date": "2026-07-23T11:00:00Z",
      "created_at": "2026-07-23T10:00:00Z"
    },
    ...
  ],
  "pagination": {...}
}
```

#### 2. Get Transaction by ID
```
GET /api/v1/transactions/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {...}
}
```

#### 3. Create Transaction
```
POST /api/v1/transactions
Authorization: Bearer <token>
Content-Type: application/json

{
  "reservation_id": 1,
  "payment_method": "transfer",
  "amount": 300000,
  "description": "Pembayaran reservasi futsal"
}

Response (201 Created):
{
  "success": true,
  "message": "Transaksi berhasil dibuat",
  "data": {
    ...
    "transaction_code": "TRX20260723001"
  }
}
```

#### 4. Update Transaction Status (Admin Only)
```
PUT /api/v1/transactions/{id}/status
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "completed",
  "payment_date": "2026-07-23T11:00:00Z"
}

Response (200 OK):
{
  "success": true,
  "message": "Status transaksi diupdate",
  "data": {...}
}
```

---

### User Endpoints (Admin Only)

#### 1. Get All Users
```
GET /api/v1/users?page=1&role=customer&status=active
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Budi",
      "email": "budi@email.com",
      "phone": "08111111111",
      "role": "customer",
      "status": "active",
      "created_at": "2026-07-23T10:00:00Z"
    },
    ...
  ],
  "pagination": {...}
}
```

#### 2. Get User by ID
```
GET /api/v1/users/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {...}
}
```

#### 3. Update User (Admin Only)
```
PUT /api/v1/users/{id}
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Budi Santoso",
  "phone": "08111111111",
  "role": "customer",
  "status": "active",
  "address": "Alamat baru"
}

Response (200 OK):
{
  "success": true,
  "message": "User berhasil diupdate",
  "data": {...}
}
```

#### 4. Delete User (Admin Only)
```
DELETE /api/v1/users/{id}
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "message": "User berhasil dihapus"
}
```

---

### Dashboard Endpoints

#### 1. Get Dashboard Statistics (Admin)
```
GET /api/v1/dashboard/statistics
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {
    "total_users": 5,
    "total_fields": 5,
    "total_reservations": 10,
    "total_revenue": 1500000,
    "pending_reservations": 3,
    "confirmed_reservations": 5,
    "completed_reservations": 2
  }
}
```

#### 2. Get Dashboard Charts (Admin)
```
GET /api/v1/dashboard/charts
Authorization: Bearer <token>

Response (200 OK):
{
  "success": true,
  "data": {
    "reservations_7days": [
      {"date": "2026-07-17", "count": 2},
      {"date": "2026-07-18", "count": 1},
      ...
    ],
    "revenue_7days": [
      {"date": "2026-07-17", "total": 300000},
      {"date": "2026-07-18", "total": 150000},
      ...
    ],
    "field_popularity": [
      {"id": 1, "name": "Futsal 1", "total": 5},
      {"id": 2, "name": "Futsal 2", "total": 3},
      ...
    ]
  }
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email sudah terdaftar"],
    "phone": ["Nomor telepon tidak valid"]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Autentikasi diperlukan",
  "code": "UNAUTHENTICATED"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Anda tidak memiliki izin untuk aksi ini",
  "code": "UNAUTHORIZED"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource tidak ditemukan",
  "code": "NOT_FOUND"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {...}
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server",
  "code": "SERVER_ERROR"
}
```

---

## Rate Limiting

- Limit: 60 requests per minute
- Headers:
  - `X-RateLimit-Limit: 60`
  - `X-RateLimit-Remaining: 59`
  - `X-RateLimit-Reset: 1626691500`

---

## Examples

### Using cURL
```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "budi@email.com",
    "password": "password"
  }'

# Get Fields
curl -X GET http://localhost:8000/api/v1/fields \
  -H "Authorization: Bearer <token>" \
  -H "Accept: application/json"

# Create Reservation
curl -X POST http://localhost:8000/api/v1/reservations \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "field_id": 1,
    "reservation_date": "2026-07-24",
    "start_time": "10:00",
    "end_time": "12:00"
  }'
```

### Using JavaScript (Fetch)
```javascript
// Login
const response = await fetch('http://localhost:8000/api/v1/auth/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'budi@email.com',
    password: 'password'
  })
});
const { data } = await response.json();
const token = data.token;

// Get Fields
const fieldsResponse = await fetch('http://localhost:8000/api/v1/fields', {
  headers: { 'Authorization': `Bearer ${token}` }
});
const fields = await fieldsResponse.json();
```

### Using Python (Requests)
```python
import requests

# Login
response = requests.post('http://localhost:8000/api/v1/auth/login', json={
    'email': 'budi@email.com',
    'password': 'password'
})
token = response.json()['data']['token']

# Get Fields
headers = {'Authorization': f'Bearer {token}'}
fields = requests.get('http://localhost:8000/api/v1/fields', headers=headers)
print(fields.json())
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-07-23 | Initial Release |

---

**API Documentation Updated**: 2026-07-24
**Status**: Stable ✅
