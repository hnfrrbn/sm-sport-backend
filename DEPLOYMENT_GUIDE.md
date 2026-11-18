# SM Sport Center - Setup & Deployment Guide

## 🎯 Quick Start (5 Menit)

### 1. Prerequisites
- PHP 8.2+
- Composer
- SQLite atau MySQL
- Laragon (Windows) atau Web Server lain

### 2. Instalasi
```bash
# Navigate ke folder project
cd c:\laragon\www\sm-sport-backend

# Install dependencies
composer install

# Generate key
php artisan key:generate

# Create database dan seed
php artisan migrate --seed

# Start server
php artisan serve --port=8000
```

### 3. Access Application
- **URL**: http://localhost:8000
- **Admin Login**: admin@gmail.com / password
- **Customer Login**: budi@email.com / password

---

## 📦 Production Deployment Checklist

### Pre-Deployment
- [ ] Setup production server (Linux recommended)
- [ ] Install PHP 8.2+ with required extensions
- [ ] Install MySQL 8.0+ or PostgreSQL 12+
- [ ] Install Composer
- [ ] Install Node.js 16+ (for npm assets)
- [ ] Setup SSH key authentication
- [ ] Configure SSL certificate (Let's Encrypt)

### Application Setup
```bash
# Clone repository
git clone <repository-url> sm-sport-backend
cd sm-sport-backend

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy environment file
cp .env.example .env

# Update .env dengan production settings:
# APP_ENV=production
# APP_DEBUG=false
# DB_CONNECTION=mysql
# DB_HOST=db.example.com
# DB_DATABASE=sm_sport
# DB_USERNAME=user
# DB_PASSWORD=secure_password
# APP_URL=https://smsport.example.com

# Generate application key
php artisan key:generate

# Create database tables
php artisan migrate --force

# Seed initial data
php artisan db:seed --class=UserSeeder --force
php artisan db:seed --class=FieldSeeder --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Install npm dependencies (if using npm assets)
npm install
npm run build

# Set proper permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Change ownership
chown -R www-data:www-data .
```

### Web Server Configuration

#### Apache (httpd.conf or .htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ /index.php [QSA,L]
</IfModule>
```

#### Nginx (nginx.conf)
```nginx
server {
    listen 80;
    server_name smsport.example.com;
    root /var/www/sm-sport-backend/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fast_uri;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Database Backup
```bash
# Daily backup script (cron job)
# Create file: /usr/local/bin/backup-smsport.sh

#!/bin/bash
DB_USER="root"
DB_PASS="password"
DB_NAME="sm_sport"
BACKUP_DIR="/backups/smsport"
DATE=$(date +\%Y\%m\%d_\%H\%M\%S)

mkdir -p $BACKUP_DIR
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Keep only last 30 days of backups
find $BACKUP_DIR -mtime +30 -delete
```

### Monitoring & Logging
- [ ] Setup log rotation (/var/log/smsport/laravel.log)
- [ ] Configure monitoring (e.g., Datadog, New Relic)
- [ ] Setup uptime monitoring
- [ ] Configure email notifications for errors
- [ ] Setup database backups (daily)
- [ ] Configure SSL certificate auto-renewal

### Security Settings

#### .env Production Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<your-app-key>
APP_URL=https://smsport.example.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=sm_sport
DB_USERNAME=smsport_user
DB_PASSWORD=<strong-password>

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<username>
MAIL_PASSWORD=<password>
MAIL_FROM_ADDRESS=noreply@smsport.com
MAIL_FROM_NAME="SM Sport Center"

# Session
SESSION_DRIVER=cookie
CACHE_DRIVER=redis
QUEUE_CONNECTION=database

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Performance Optimization

1. **Enable Query Caching**
```php
// config/database.php
'redis' => [
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', 6379),
    ],
]
```

2. **Enable Database Connection Pooling**
```bash
# Install if using MySQL
apt-get install percona-server-client
```

3. **Setup CDN for Static Assets**
- Upload /public/images, /public/css, /public/js to CDN
- Update app to serve from CDN URLs

4. **Enable GZIP Compression**
```nginx
gzip on;
gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss;
gzip_min_length 1000;
```

### SSL/HTTPS Setup

```bash
# Using Let's Encrypt with Certbot
apt-get install certbot python3-certbot-nginx

# Generate certificate
certbot certonly --nginx -d smsport.example.com -d www.smsport.example.com

# Auto-renew certificate
# Add to crontab:
# 0 12 * * * /usr/bin/certbot renew --quiet
```

### Monitoring Commands

```bash
# Check application logs
tail -f storage/logs/laravel.log

# Monitor database
mysql -u root -p sm_sport
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Threads%';

# Check server resources
free -h
df -h
top

# Check web server status
systemctl status nginx
systemctl status apache2
systemctl status php8.2-fpm
```

---

## 🚀 Deployment via Docker (Optional)

### Dockerfile
```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache && php artisan route:cache

EXPOSE 9000

CMD ["php-fpm"]
```

### docker-compose.yml
```yaml
version: '3.8'
services:
  app:
    build: .
    container_name: smsport_app
    working_dir: /app
    volumes:
      - ./:/app
    ports:
      - "9000:9000"
    networks:
      - smsport
    depends_on:
      - db

  web:
    image: nginx:alpine
    container_name: smsport_web
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/app
      - ./nginx.conf:/etc/nginx/nginx.conf
    networks:
      - smsport
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: smsport_db
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: sm_sport
      MYSQL_USER: smsport_user
      MYSQL_PASSWORD: user_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - smsport
    ports:
      - "3306:3306"

networks:
  smsport:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

### Deploy with Docker
```bash
# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --seed

# View logs
docker-compose logs -f app
```

---

## 📊 Monitoring & Alerts

### Health Check Endpoint (optional)
Add to routes/api.php:
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'uptime' => app('uptime'),
    ]);
});
```

### Backup Strategy
- Daily full database backup
- Weekly application code backup
- Monthly offsite backup (S3, Google Cloud)
- Test restore quarterly

### Disaster Recovery Plan
- Keep backup server ready
- Document recovery procedures
- Test recovery annually
- Maintain runbook for common issues

---

## 🔄 Continuous Integration/Deployment (Optional)

### GitHub Actions
```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install Dependencies
        run: composer install
      
      - name: Run Tests
        run: ./vendor/bin/phpunit
      
      - name: Deploy to Server
        run: |
          # Deploy commands
```

---

## 📞 Support & Troubleshooting

### Common Issues

**Issue**: 500 Internal Server Error
- Check error logs: `tail -f storage/logs/laravel.log`
- Verify permissions: `chmod -R 755 storage/`
- Clear cache: `php artisan cache:clear`

**Issue**: Database Connection Error
- Verify database credentials in .env
- Check MySQL/PostgreSQL is running
- Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`

**Issue**: High Memory Usage
- Check query optimization
- Enable query caching
- Use pagination on large datasets
- Monitor with `free -h` and `top`

**Issue**: Slow Page Load
- Enable query logging to find slow queries
- Add database indexes
- Cache frequently used data
- Use CDN for static assets

---

## 📈 Scaling Recommendations

1. **Database Replication**: Setup read replicas for reporting
2. **Caching Layer**: Implement Redis for sessions and cache
3. **Load Balancer**: Distribute traffic across multiple app servers
4. **Static Asset CDN**: Serve images/JS/CSS from CDN
5. **Queue System**: Move heavy tasks to background jobs

---

**Last Updated**: 2026-07-24  
**Maintainer**: SM Sport Center Team
