<p align="center"><img src="https://via.placeholder.com/150x150?text=HIMATEKOM" width="150" alt="HIMATEKOM Logo"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# AKOM-demo-himpunan - Computer Engineering Student Association Management System

AKOM-demo-himpunan is a comprehensive organization management system designed specifically for HIMATEKOM (Himpunan Mahasiswa Teknik Komputer - Computer Engineering Student Association). This web application streamlines administrative tasks, event management, documentation, and communication within the student organization.





## Features

The system includes the following key features:

### User Management

-   Role-based access control (admin, executive, staff, member)
-   User profiles with department assignments
-   Digital signature capabilities for authorized users

### Event Management

-   Create and publish events
-   Track registrations and attendance
-   Event status workflow (draft, pending, approved, published)
-   Post-event reporting (LPJ - Laporan Pertanggung Jawaban)

### Document Management

-   Official letters with templates
-   Document repository with categorization
-   Permission-based access to sensitive documents
-   Version control and approval workflows

### Financial Management

-   Track organizational budget and expenses
-   Financial transaction records
-   Financial reporting capabilities

### Communication Tools

-   News publication system
-   Photo galleries for event documentation
-   Member notifications and updates

### Administrative Tools

-   Department management
-   Admin dashboard with key metrics
-   Report generation and templates

## Technology Stack

-   **Framework**: Laravel 12
-   **Frontend**: Blade templates, Tailwind CSS
-   **Admin Panel**: Filament
-   **Authorization**: Spatie Laravel Permission
-   **Database**: MySQL
-   **Authentication**: Laravel Sanctum

## Installation

### Prerequisites

-   PHP >= 8.2
-   Composer
-   MySQL
-   Node.js & NPM

### Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/your-username/AKOM-demo-himpunan.git
cd AKOM-demo-himpunan
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install frontend dependencies:

```bash
npm install
```

4. Create and configure .env file:

```bash
cp .env.example .env
```

Update the .env file with your database credentials

5. Generate application key:

```bash
php artisan key:generate
```

6. Run migrations and seed the database:

```bash
php artisan migrate
php artisan db:seed
```

7. Build frontend assets:

```bash
npm run build
```

8. Create storage link:

```bash
php artisan storage:link
```

9. Start the development server:

```bash
php artisan serve
```

## Default Users

After seeding, the following test users are available:

-   **Admin**: admin@himatekom.org | password: password
-   **Chairperson**: chair@himatekom.org | password: password
-   **Secretary**: secretary@himatekom.org | password: password
-   **Treasurer**: treasurer@himatekom.org | password: password
-   **Member**: member1@himatekom.org | password: password

## Modules

### Dashboard

The dashboard provides a quick overview of the organization's activities, upcoming events, pending documents, and recent news.

### Events

Manage organization events including workshops, meetings, competitions, and community service activities.

### LPJ (Activity Reports)

Create and manage post-event reports with customizable templates and approval workflows.

### Letters

Generate and track official organization letters with proper numbering and digital signatures.

### Documents

Store and organize organizational documents with proper categorization and access control.

### Galleries

Manage photos and media from various organization events and activities.

## Contributing

Contributions to the AKOM-demo-himpunan project are welcome. Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

AKOM-demo-himpunan is powered by Laravel and built for HIMATEKOM to improve organizational efficiency and management.
