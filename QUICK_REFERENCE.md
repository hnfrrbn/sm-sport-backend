# ⚡ SM Sport Center - Quick Reference Guide

## 🚀 One-Minute Startup

```bash
cd c:\laragon\www\sm-sport-backend
composer install && php artisan key:generate && php artisan migrate --seed && php artisan serve --port=8000
```

**Access**: http://localhost:8000

---

## 🔑 Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@gmail.com | password |
| **Customer** | budi@email.com | password |

---

## 📁 File Structure Quick Map

```
Core Logic
├── app/Http/Controllers/          (Business logic)
├── app/Models/                    (Database models)
├── database/migrations/           (Schema)
└── database/seeders/              (Sample data)

Views & Routes
├── resources/views/               (20 HTML templates)
├── routes/web.php                (All routes)
└── config/                        (Configuration)

Documentation
├── README.md                      (Start here)
├── DOKUMENTASI.md                (Full docs)
├── API_DOCUMENTATION.md          (API reference)
├── DEPLOYMENT_GUIDE.md           (Production)
└── FEATURE_SUMMARY.md            (What's included)
```

---

## 🔗 Important URLs

| Path | Purpose |
|------|---------|
| `/` | Homepage |
| `/register` | New account |
| `/login` | Sign in |
| `/dashboard` | Customer home |
| `/admin/dashboard` | Admin panel |
| `/fields` | Browse fields |
| `/reservations` | Create booking |
| `/my-reservations` | View bookings |
| `/my-transactions` | View payments |

---

## 🛠️ Common Tasks

### Clear Cache
```bash
php artisan cache:clear && php artisan route:clear && php artisan view:clear
```

### Refresh Database
```bash
php artisan migrate:refresh --seed
```

### Debug Mode
```bash
php artisan tinker
```

### View Routes
```bash
php artisan route:list
```

### Test SQL
```bash
sqlite3 storage/database.sqlite
```

---

## 📊 Database Quick Reference

| Table | Purpose | Key Fields |
|-------|---------|-----------|
| `users` | Auth & profiles | id, email, role, status |
| `fields` | Lapangan | id, name, type, price_per_hour |
| `reservations` | Bookings | id, user_id, field_id, start_time, end_time |
| `transactions` | Payments | id, transaction_code, amount, status |
| `activity_logs` | Audit trail | id, user_id, action, old_values, new_values |

---

## 🔐 Admin Features

### Dashboard
- View statistics (users, fields, reservations, revenue)
- See charts (7-day trends, revenue, popularity)

### Fields Management
- `/admin/fields` - List all fields
- Create, edit, delete fields
- Set pricing & status

### User Management
- `/admin/users` - View all users
- Change user status
- Delete users

### Reservations
- `/admin/reservations` - Approve/manage bookings
- Update reservation status
- Delete reservations

### Transactions
- `/admin/transactions` - View all payments
- Update payment status

---

## 👤 Customer Features

### Dashboard
- `/dashboard` - Overview & recent bookings

### Fields
- `/fields` - Browse available fields

### Reservations
- `/reservations` - Create new booking
- `/my-reservations` - View own bookings
- Cancel pending reservations

### Profile
- `/profile/edit` - Update personal info

### Transactions
- `/my-transactions` - View payment history

---

## 🎯 Key Features Explained

### ⭐ Double Booking Prevention
**How it works:**
1. Customer selects field + date + time
2. System checks for overlapping reservations
3. If conflict found → Error message shown
4. Otherwise → Reservation created

**Code**: `ReservationController::store()` line ~85

### 💰 Auto Price Calculation
**How it works:**
1. Customer selects start & end time
2. Duration calculated: (end_time - start_time)
3. Price calculated: duration × field's price_per_hour
4. Total shown to customer

**Code**: `Reservation::boot()` method

### 📝 Activity Logging
**What's logged:**
- User actions (create, update, delete)
- Old values → New values (JSON)
- IP address
- User agent
- Timestamp

**Code**: `ActivityLog::log()` method

---

## 🔍 Debugging Tips

### Issue: 500 Internal Error
```bash
# 1. Check logs
tail -f storage/logs/laravel.log

# 2. Clear cache
php artisan cache:clear

# 3. Check permissions
chmod -R 775 storage/
```

### Issue: Database Connection Failed
```bash
# 1. Verify .env settings
cat .env | grep DB_

# 2. Create database if missing
php artisan migrate --seed

# 3. Test connection
php artisan tinker
> DB::connection()->getPDO();
```

### Issue: Route Not Found
```bash
# 1. Clear route cache
php artisan route:clear

# 2. Verify routes file
php artisan route:list | grep {route_name}
```

---

## 📖 Documentation Map

```
START HERE
↓
README.md (overview)
↓
Choose one:
├─ DOKUMENTASI.md (full guide in Indonesian)
├─ API_DOCUMENTATION.md (REST API)
└─ DEPLOYMENT_GUIDE.md (production setup)
↓
FEATURE_SUMMARY.md (what's included)
```

---

## 💾 Backup & Restore

### Backup Database
```bash
# SQLite
cp storage/database.sqlite storage/database.backup.sqlite

# MySQL
mysqldump -u root -p sm_sport > backup.sql
```

### Restore Database
```bash
# SQLite
cp storage/database.backup.sqlite storage/database.sqlite

# MySQL
mysql -u root -p sm_sport < backup.sql
```

---

## 🌐 API Quick Reference

### Get Auth Token
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"budi@email.com","password":"password"}'
```

### Get Fields
```bash
curl -X GET http://localhost:8000/api/v1/fields \
  -H "Authorization: Bearer {token}"
```

### Create Reservation
```bash
curl -X POST http://localhost:8000/api/v1/reservations \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "field_id":1,
    "reservation_date":"2026-07-24",
    "start_time":"10:00",
    "end_time":"12:00"
  }'
```

Full API docs: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

---

## 🧪 Testing Checklist

- [ ] Login as admin
- [ ] View admin dashboard
- [ ] Create a new field
- [ ] Login as customer
- [ ] Create a reservation
- [ ] Try double booking (should fail)
- [ ] View reservations list
- [ ] Edit profile
- [ ] Logout
- [ ] Login again to verify session

---

## 📞 Quick Help

### Where is...?

| Question | Answer |
|----------|--------|
| Login logic? | `app/Http/Controllers/Auth/AuthController.php` |
| Double booking check? | `app/Http/Controllers/ReservationController.php` |
| Database schema? | `database/migrations/` |
| Sample data? | `database/seeders/` |
| HTML templates? | `resources/views/` |
| All routes? | `routes/web.php` |
| Configuration? | `.env` file |
| Errors? | `storage/logs/laravel.log` |

---

## ⚙️ Configuration Quick Check

```bash
# Verify key is set
grep APP_KEY .env

# Check database connection
grep DB_ .env

# View current environment
grep APP_ENV .env

# Check debug mode
grep APP_DEBUG .env
```

---

## 📱 Mobile Testing

Use responsive design mode in browser:
- Press `F12` to open developer tools
- Click device toggle (top-left)
- Select mobile device
- Test sidebar collapse
- Test form responsiveness

---

## 🎨 Customize

### Change Colors
Edit: `resources/views/layouts/app.blade.php`
```html
<!-- Update color classes -->
<div class="sidebar" style="background-color: #YOUR-COLOR;">
```

### Change Site Name
Edit: `.env`
```
APP_NAME="Your Site Name"
```

### Change Logo
Replace: `public/logo.png`

---

## 🚀 Deploy to Production

Quick deployment:
```bash
# 1. Upload files to server
# 2. Run setup commands
composer install --no-dev
php artisan config:cache
php artisan migrate --force
# 3. Set permissions
chmod -R 775 storage/ bootstrap/cache/
# 4. Configure web server (see DEPLOYMENT_GUIDE.md)
```

---

## 📊 Performance Tips

1. **Use indexes** - Already done in migrations
2. **Enable query caching** - Configure in `.env`
3. **Use pagination** - Already implemented
4. **Cache routes** - `php artisan route:cache`
5. **Cache views** - `php artisan view:cache`
6. **Use CDN** - For static assets in production

---

## 🔐 Security Quick Check

- [x] CSRF tokens in forms
- [x] Password hashing (bcrypt)
- [x] Input validation
- [x] SQL injection prevention (Eloquent)
- [x] XSS prevention (Blade escaping)
- [x] Session security
- [x] Authorization middleware
- [x] Activity logging

---

## 📞 Support Resources

- **Documentation**: See docs/ folder
- **Errors**: Check storage/logs/laravel.log
- **Database Help**: Use `php artisan tinker`
- **API Issues**: See API_DOCUMENTATION.md
- **Deployment**: See DEPLOYMENT_GUIDE.md

---

## 🎓 Next Steps

1. **Run the app** - Follow startup instructions
2. **Test workflows** - Use testing checklist
3. **Read full docs** - See DOKUMENTASI.md
4. **Deploy** - Follow DEPLOYMENT_GUIDE.md
5. **Extend** - Add features as needed

---

**Last Updated**: 2026-07-24  
**Version**: 1.0.0  
**Status**: ✅ Ready to Use
