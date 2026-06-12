<div align="center">

# 🎓 ITE-APP — Education Management System

**A comprehensive multi-role education platform built with Laravel & Filament**

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-Admin-F59E0B?style=for-the-badge)](https://filamentphp.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📌 Overview

ITE-APP is a full-featured **Education Management System** designed to streamline the management of students, instructors, courses, and payments within an educational institution. The system supports multiple user roles with fine-grained permission control and integrates a secure payment processing module.

---

## ✨ Features

- 🔐 **Multi-Role Access Control** — Student, Instructor, and Admin roles with Spatie RBAC
- 🏫 **Course Management** — Create, assign, and manage courses with enrollment tracking
- 💳 **Payment Integration** — Secure payment processing for course subscriptions
- 🛡 **JWT Authentication** — Stateless, secure API authentication
- 📊 **Admin Dashboard** — Powered by Filament for rich data management UI
- 🌐 **RESTful API** — Clean API architecture for potential mobile integration
- 🗄 **Database Optimization** — Normalized schema with optimized Eloquent queries

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2, Laravel 12 |
| **Admin Panel** | Filament v3 |
| **Database** | MySQL 8.0 |
| **Authentication** | JWT (tymon/jwt-auth) |
| **Authorization** | Spatie Laravel-Permission |
| **Frontend** | Blade, Tailwind CSS, Vite |
| **Dev Tools** | Docker, Postman, Git |

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL 8.0+
- Node.js & NPM

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/Yasmine772/ITE-APP.git
cd ITE-APP

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install && npm run build

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
# DB_DATABASE=ite_app
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Serve the application
php artisan serve
```

### Access

| Panel | URL | Credentials (after seeding) |
|---|---|---|
| Admin Dashboard | `http://localhost:8000/admin` | admin@example.com / password |
| API Base | `http://localhost:8000/api` | — |

---

## 📁 Project Structure

```
ITE-APP/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # API & Web controllers
│   │   └── Middleware/      # Auth & Role middleware
│   ├── Models/              # Eloquent models
│   └── Filament/            # Admin panel resources
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Initial data seeders
├── routes/
│   ├── api.php              # API routes (JWT protected)
│   └── web.php              # Web routes
└── tests/                   # Feature & Unit tests
```

---

## 🔑 API Endpoints (Sample)

```
POST   /api/auth/login          → Login & get JWT token
POST   /api/auth/register       → Register new user
GET    /api/courses             → List all courses
POST   /api/courses             → Create course (Admin/Instructor)
POST   /api/enrollments         → Enroll in a course
GET    /api/payments            → Payment history
```

> Full API documentation available via Postman collection (see `/docs` folder)

---

## 🗃 Database Schema (Overview)

```
users           → id, name, email, password, role
courses         → id, title, description, price, instructor_id
enrollments     → id, user_id, course_id, enrolled_at
payments        → id, user_id, course_id, amount, status
permissions     → Spatie RBAC tables
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m 'feat: add your feature'`
4. Push to the branch: `git push origin feature/your-feature`
5. Open a Pull Request

---

## 👩‍💻 Author

**Yasmine Ebrahim**
Backend Developer | Laravel Specialist

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=flat-square&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/yassmine-ibrahim)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=flat-square&logo=github&logoColor=white)](https://github.com/Yasmine772)
[![Email](https://img.shields.io/badge/Email-D14836?style=flat-square&logo=gmail&logoColor=white)](mailto:yasmineebrahim79@gmail.com)

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<div align="center">

*Built using Laravel & Filament*

</div>
