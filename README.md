# Task Schedule Management System

A Laravel-based application for managing and scheduling tasks across development teams. The system allows for efficient task allocation, scheduling, and monitoring of developer workloads.

## 🚀 Features

- Task scheduling and management
- Developer workload tracking
- Weekly progress monitoring
- Task complexity tracking
- Provider management system
- Duration-based task organization

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js & NPM (for asset compilation)
- Laravel 10.x

## 🛠 Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd [project-directory]
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy the environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations:
```bash
php artisan migrate
```

## 💻 Usage

### Managing Providers
Access the providers management interface at `/providers`. Here you can:
- View all providers
- Create new providers
- Edit existing providers
- Delete providers

### Task Scheduling
The task scheduling system allows you to:
- Assign tasks to developers
- Track task complexity (Low, Medium, High)
- Monitor task duration
- View weekly progress and workload distribution

## 🏗 Project Structure

Key project directories:
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProviderController.php
│   │   └── ScheduleController.php
│   └── ...
resources/
├── views/
│   ├── components/
│   │   ├── developer-card.blade.php
│   │   └── stats-card.blade.php
│   └── schedule.blade.php
routes/
└── web.php
```

## 🔄 Available Routes

- `GET /providers` - List all providers
- `GET /providers/create` - Create new provider form
- `POST /providers` - Store new provider
- `GET /providers/{provider}` - Show provider details
- `GET /providers/{provider}/edit` - Edit provider form
- `PUT/PATCH /providers/{provider}` - Update provider
- `DELETE /providers/{provider}` - Delete provider
- `GET /` - View task schedule

## 🎨 Front-end Assets

The project uses:
- Bootstrap 5.1.3
- Bootstrap Icons
- Custom Blade components

## 📚 Documentation References

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs/5.1/getting-started/introduction/)
