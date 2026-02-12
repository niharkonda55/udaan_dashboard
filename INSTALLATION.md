# Installation Guide

## Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server

## Step-by-Step Installation

### 1. Install Dependencies
```bash
composer install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
Edit `.env` file and configure your database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=udaan_events
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed Database
```bash
php artisan db:seed
```

This will create default users:
- **Admin**: admin@udaan.com / password
- **Cameraman**: cameraman@udaan.com / password
- **Editor**: editor@udaan.com / password

### 6. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### 7. Access the Application
Navigate to `http://localhost:8000/login` and login with any of the default credentials.

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Ensure proper file permissions on `storage/` and `bootstrap/cache/` directories

## Troubleshooting

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

