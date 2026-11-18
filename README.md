# 🏆 SM Sport Center - Sistem Reservasi Lapangan Olahraga

> Aplikasi web terintegrasi untuk mengelola reservasi lapangan olahraga (Futsal dan Badminton) dengan fitur lengkap termasuk autentikasi, manajemen data, dan laporan analitik.

![Status](https://img.shields.io/badge/Status-Production%20Ready-green)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📋 Quick Navigation

- [Features](#-fitur-utama) | [Quick Start](#-quick-start) | [Documentation](#-dokumentasi) | [Deployment](#-deployment)

---

## ✨ Fitur Utama

### Core Features
- ✅ **Autentikasi & Otorisasi** - Registration, Login, Role-based access
- ✅ **Admin Dashboard** - Real-time statistics dengan Chart.js
- ✅ **Field Management** - CRUD dengan multiple types & dynamic pricing
- ✅ **Reservation System** - Booking dengan **automatic double-booking prevention** ⭐
- ✅ **Transaction Tracking** - Payment management dengan unique codes
- ✅ **Activity Logging** - Complete audit trail dengan IP tracking
- ✅ **User Management** - Admin control dengan status management
- ✅ **Responsive UI** - Bootstrap 5 dengan mobile-friendly design

---

## 🚀 Quick Start

### Installation (5 menit)
```bash
cd c:\laragon\www\sm-sport-backend
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8000
```

### 🌐 Access
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@gmail.com | password |
| Customer | budi@email.com | password |

**URL**: http://localhost:8000

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php           # Dashboard & statistics
│   ├── FieldController.php           # Field CRUD
│   ├── ReservationController.php     # Booking logic (double-booking check)
│   ├── TransactionController.php     # Payment tracking
│   ├── UserController.php            # User management
│   ├── ProfileController.php         # Profile management
│   └── Auth/AuthController.php       # Authentication

app/Models/
├── User.php                          # Relationships: reservations, transactions, logs
├── Field.php                         # Relationships: reservations, availability check
├── Reservation.php                   # Relationships: user, field, transaction
├── Transaction.php                   # Auto-generated transaction codes
└── ActivityLog.php                   # Comprehensive audit trail

database/
├── migrations/                       # 5 tables (users, fields, reservations, transactions, logs)
├── seeders/                          # Sample data (1 admin + 5 customers + 5 fields)
└── SCHEMA.sql                        # Complete SQL export

resources/views/
├── layouts/app.blade.php             # Master layout (responsive sidebar)
├── auth/                             # Login & registration forms
├── admin/                            # Admin dashboard
├── fields/                           # Field views (list, create, edit, show)
├── reservations/                     # Booking & history views
├── transactions/                     # Transaction views
└── users/                            # User management views
```

---

## 🗄️ Database Schema

**5 Tables** with relationships:
- `users` - Authentication & roles (admin/customer)
- `fields` - Lapangan dengan pricing
- `reservations` - Booking dengan double-booking prevention
- `transactions` - Payment records dengan unique codes
- `activity_logs` - Audit trail (JSON-based)

**Features**: Foreign keys, indexes, timestamps, unique constraints

---

## 📊 Default Data

### Admin
```
Email: admin@gmail.com | Password: password
```

### Sample Customers (All password: `password`)
- Budi Santoso (budi@email.com)
- Siti Nurhaliza (siti@email.com)
- Ahmad Wijaya (ahmad@email.com)
- Rini Handayani (rini@email.com)
- Doni Pratama (doni@email.com)

### Sample Fields
- Futsal 1 & 2 - Rp 150.000/jam (capacity: 10)
- Badminton 1-3 - Rp 80.000/jam (capacity: 4)

---

## 🔐 Security

- CSRF Protection (middleware enabled)
- XSS Prevention (Blade escaping)
- SQL Injection Prevention (Eloquent ORM)
- Password Hashing (Bcrypt)
- Session Security
- Role-based Access Control
- Complete Audit Logging

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| [DOKUMENTASI.md](DOKUMENTASI.md) | Lengkap (Bahasa Indonesia) |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | REST API Reference |
| [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | Production deployment |
| [database/SCHEMA.sql](database/SCHEMA.sql) | SQL schema export |

---

## ⚡ Useful Commands

```bash
# Clear caches
php artisan cache:clear && php artisan route:clear && php artisan view:clear

# Fresh migration
php artisan migrate:refresh --seed

# Tinker console
php artisan tinker

# View routes
php artisan route:list

# Check migrations
php artisan migrate:status

# Run tests
php artisan test
```

---

## 🚀 Deployment

### Local
```bash
php artisan serve --port=8000
```

### Production
See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

### Docker
```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

---

## 🧪 Testing Workflow

1. Login customer: budi@email.com
2. View fields
3. Create reservation
4. Check double-booking prevention
5. Login admin to approve

---

## 🤝 Contributing

Pull requests welcome! Follow conventional commits.

---

## 📞 Support

### Troubleshooting
- **500 Error**: `php artisan cache:clear` + `tail -f storage/logs/laravel.log`
- **DB Error**: Check .env credentials + `php artisan migrate:refresh --seed`
- **Permissions**: `chmod -R 775 storage/ bootstrap/cache/`

### Contact
- Email: support@smsport.com
- WhatsApp: +62-812-3456-789

---

## 📄 License

MIT License

---

## 👨‍💻 Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: SQLite / MySQL 5.7+
- **Frontend**: Bootstrap 5, JavaScript
- **Charts**: Chart.js
- **Icons**: Bootstrap Icons

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap 5
- Chart.js
- SweetAlert2

---

## 📈 Future Enhancements

- [ ] Mobile app (React Native)
- [ ] Payment gateway integration
- [ ] Email/SMS notifications
- [ ] Advanced analytics
- [ ] Multi-language support
- [ ] Two-factor authentication
- [ ] Membership packages

---

<div align="center">

**Built with ❤️ for SM Sport Center**

[API Docs](API_DOCUMENTATION.md) • [Deployment](DEPLOYMENT_GUIDE.md) • [Full Docs](DOKUMENTASI.md)

Version 1.0.0 | Last Updated: 2026-07-24

</div>

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
