# Quick Start Guide

## Prerequisites Check
- PHP 8.1+ installed
- Composer installed
- MySQL running
- Web server (or use PHP built-in server)

## Installation Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=udaan_events
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Create Database
```sql
CREATE DATABASE udaan_events;
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Start Server
```bash
php artisan serve
```

### 7. Access Application
Open browser: `http://localhost:8000/login`

## Default Login Credentials

After seeding, use these credentials:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@udaan.com | password |
| Cameraman | cameraman@udaan.com | password |
| Editor | editor@udaan.com | password |

## Testing the Workflow

### As Admin:
1. Login as admin@udaan.com
2. Click "Create New Project"
3. Fill in project details
4. Assign cameraman and editor (only idle users shown)
5. View project timeline

### As Cameraman:
1. Login as cameraman@udaan.com
2. View assigned projects
3. Mark shooting started
4. Mark shooting completed
5. Add raw media information (physical drive or online link)

### As Editor:
1. Login as editor@udaan.com
2. View assigned projects
3. See cameraman's raw media information
4. Mark editing started
5. Mark ready for review
6. Add final delivery information

### Back to Admin:
1. Review project
2. Approve or send for rework
3. Mark as completed

## Common Issues

### Issue: "Class not found"
**Solution**: Run `composer dump-autoload`

### Issue: "Permission denied" on storage
**Solution**: 
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: "SQLSTATE[HY000] [2002] Connection refused"
**Solution**: Check MySQL is running and credentials in `.env` are correct

### Issue: "Route not found"
**Solution**: Clear route cache: `php artisan route:clear`

## Next Steps

- Customize the UI/UX as needed
- Add more users via database or create user management
- Configure email settings if needed
- Set up proper logging and monitoring
- Deploy to production server

