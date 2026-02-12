# Project Structure

## Directory Overview

```
udaan_events/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminDashboardController.php
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── Cameraman/
│   │   │   │   └── CameramanDashboardController.php
│   │   │   ├── Editor/
│   │   │   │   └── EditorDashboardController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       ├── RoleMiddleware.php
│   │       └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Project.php
│   │   └── ActivityLog.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── RouteServiceProvider.php
├── bootstrap/
│   ├── app.php
│   └── cache/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── ...
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_projects_table.php
│   │   └── 2024_01_01_000003_create_activity_logs_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   ├── index.php
│   └── .htaccess
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   └── projects/
│       │       ├── create.blade.php
│       │       └── show.blade.php
│       ├── cameraman/
│       │   ├── dashboard.blade.php
│       │   └── projects/
│       │       └── show.blade.php
│       └── editor/
│           ├── dashboard.blade.php
│           └── projects/
│               └── show.blade.php
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
└── storage/
    ├── framework/
    └── logs/
```

## Key Features

### Models
- **User**: Handles authentication and role management (admin, cameraman, editor)
- **Project**: Manages project lifecycle and workflow status
- **ActivityLog**: Tracks all project activities with timestamps

### Controllers
- **AdminDashboardController**: Full project management, assignment, approval
- **CameramanDashboardController**: Shooting status updates, raw media info
- **EditorDashboardController**: Editing status updates, final delivery info
- **LoginController**: Session-based authentication

### Workflow States
1. `created` - Project created by admin
2. `assigned` - Cameraman and editor assigned
3. `shooting` - Cameraman started shooting
4. `raw_uploaded` - Raw media handed over
5. `editing` - Editor started editing
6. `review` - Final output ready for admin review
7. `approved` - Admin approved
8. `rework` - Sent back for rework
9. `completed` - Project completed

### Security
- Role-based access control via middleware
- Users can only access their assigned projects
- CSRF protection enabled
- Session-based authentication

