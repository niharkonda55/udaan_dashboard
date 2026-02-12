# Udaan Events Dashboard

Internal web-based dashboard application for event production workflow tracking and coordination.

## Tech Stack
- Backend: PHP Laravel 10
- Database: MySQL
- Frontend: Blade templates with Bootstrap 5
- Authentication: Session-based (Laravel Auth)
- Role-based access control

## User Roles
1. **Admin** - Full access, project management, user assignment
2. **Cameraman** - View assigned projects, mark shooting status, upload raw media info
3. **Editor** - View assigned projects, mark editing status, final delivery info

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure database
4. Generate app key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Seed database: `php artisan db:seed`
7. Start server: `php artisan serve`

## Default Login Credentials

After seeding:
- **Admin**: admin@udaan.com / password
- **Cameraman**: cameraman@udaan.com / password
- **Editor**: editor@udaan.com / password

## Project Workflow

1. Created → Admin creates project
2. Assigned → Admin assigns cameraman and editor
3. Shooting → Cameraman marks shooting started/completed
4. Raw Uploaded → Cameraman marks raw media handed over
5. Editing → Editor marks editing started
6. Review → Editor marks final output ready
7. Approved/Rework → Admin approves or sends for rework
8. Completed → Project completed

## Security Notes
- No video file uploads (only links/physical handover flags)
- Role-based access control enforced via middleware
- Users can only access their assigned projects

