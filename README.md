# 🎓 School Management System (SMS)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

A comprehensive, enterprise-grade School Management System built with Laravel 12, featuring modern design patterns, complete test coverage, production-ready architecture, and AI-assisted development with Laravel Boost.

---

## 📋 Table of Contents

- [Features](#-features)
- [Architecture](#-architecture)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Testing](#-testing)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 👨‍🎓 Student Management
- Student enrollment and registration
- Class and section assignments
- Promotion and transfer workflows
- Attendance tracking
- Academic performance monitoring
- Document management
- Student profiles with guardians

### 👨‍🏫 Staff Management
- Teacher profiles and assignments
- Department organization
- Staff attendance tracking
- Payroll management
- Subject assignments
- Qualification tracking

### 📚 Academic Management
- Class and section management
- Subject management
- Timetable scheduling
- Assessment and grading
- Report card generation
- CBT (Computer-Based Testing)
- Continuous assessment tracking

### 💰 Financial Management
- Fee categories and structure
- Invoice generation
- Payment processing
- Expense tracking
- Financial reports
- Payment history
- Multi-payment method support

### 📊 Administrative Features
- Dashboard analytics
- Multi-branch support
- Role-based access control
- School profile management
- Academic session/term management
- Announcements and notices
- Messaging system
- Email notifications
- SMS notifications

### 🔐 Security & Quality
- Authorization via Policies
- Form Request validation
- Rate limiting
- Audit logging
- Activity tracking
- Secure authentication
- School-level data isolation

---

## 🏗️ Architecture

This project follows **enterprise-grade architectural patterns**:

### Design Patterns Implemented
- ✅ **Repository Pattern** - Data access abstraction
- ✅ **Service Layer Pattern** - Business logic separation
- ✅ **Observer Pattern** - Model lifecycle hooks
- ✅ **Event/Listener Pattern** - Decoupled event handling
- ✅ **Command Pattern** - CLI operations
- ✅ **Factory Pattern** - Object creation
- ✅ **Policy Pattern** - Authorization logic
- ✅ **Job Pattern** - Async task processing

### SOLID Principles
- ✅ Single Responsibility Principle
- ✅ Open/Closed Principle
- ✅ Liskov Substitution Principle
- ✅ Interface Segregation Principle
- ✅ Dependency Inversion Principle

### Project Structure
```
app/
├── Console/Commands/       # Artisan commands
├── Events/                 # Event classes
├── Http/
│   ├── Controllers/       # Request handlers
│   ├── Middleware/        # HTTP middleware
│   ├── Requests/          # Form request validation
│   └── Resources/         # API resources
├── Jobs/                  # Queue jobs
├── Listeners/             # Event listeners
├── Mail/                  # Email templates
├── Models/                # Eloquent models
├── Observers/             # Model observers
├── Policies/              # Authorization policies
├── Providers/             # Service providers
├── Repositories/          # Data access layer
└── Services/              # Business logic layer
```

---

## 📦 Requirements

- PHP >= 8.3
- Composer >= 2.x
- MySQL >= 8.0 or PostgreSQL >= 13
- Node.js >= 18.x
- NPM >= 9.x or Yarn
- Redis (optional, for queues and caching)

---

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/sms-project.git
cd sms-project
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sms_database
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate --seed
```

### 6. Storage Setup
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## ⚙️ Configuration

### Queue Configuration
For background jobs (emails, notifications):

```bash
# .env
QUEUE_CONNECTION=database

# Create jobs table
php artisan queue:table
php artisan migrate

# Start queue worker
php artisan queue:work
```

### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Cache Configuration
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 💻 Usage

### Default Login Credentials

**Super Admin**
- Email: `superadmin@sms.com`
- Password: `password`

**Principal**
- Email: `principal@school.com`
- Password: `password`

**Teacher**
- Email: `teacher@school.com`
- Password: `password`

**Student**
- Email: `student@school.com`
- Password: `password`

> ⚠️ **Change these credentials immediately in production!**

### Artisan Commands

```bash
# Promote students to next class
php artisan students:promote {school_id} {from_class} {to_class} --dry-run

# Generate report cards
php artisan reports:generate {school_id} {session_id} {term_id} --queue

# Cleanup inactive students
php artisan students:cleanup {school_id} --days=365 --dry-run

# Sync student status
php artisan students:sync-status {school_id} --fix-mismatches
```

---

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Student/StudentEnrollmentTest.php
```

### Test Coverage
- Unit Tests: Services, Repositories, Policies
- Feature Tests: Complete user workflows
- Current Coverage: 75%+ (Target: 80%+)

---

## 📚 Documentation

Comprehensive documentation is available in the `/docs` folder:

- **[📑 Documentation Index](docs/INDEX.md)** - Complete documentation guide
- **[🏗️ Architecture Guide](docs/ARCHITECTURE.md)** - Design patterns and structure
- **[🧪 Testing Guide](docs/TESTING_GUIDE.md)** - How to write and run tests
- **[🚀 Laravel Boost Setup](docs/LARAVEL_BOOST_SETUP.md)** - AI-assisted development
- **[📝 Implementation Guide](docs/IMPLEMENTATION_COMPLETE.md)** - Features overview
- **[📧 Email Setup](docs/EMAIL_DELIVERABILITY_GUIDE.md)** - Email configuration
- **[📱 Mobile UI Guide](docs/MOBILE_RESPONSIVE_BUTTONS_GUIDE.md)** - UI/UX guidelines

**Start here:** [📚 Documentation Index](docs/INDEX.md)

---

## 🛠️ Development

### Code Style
This project follows PSR-12 coding standards.

```bash
# Run code style fixer
./vendor/bin/pint

# Run static analysis
./vendor/bin/phpstan analyse
```

### Pre-commit Hooks
```bash
# Install pre-commit hooks
composer install
```

### Database Seeding
```bash
# Seed with sample data
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=StudentSeeder
```

---

## 🔒 Security

### Reporting Vulnerabilities
If you discover a security vulnerability, please email security@example.com. All security vulnerabilities will be promptly addressed.

### Security Features
- CSRF protection
- XSS prevention
- SQL injection prevention
- Rate limiting
- Authorization policies
- Encrypted passwords
- Secure session management

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure:
- Code follows PSR-12 standards
- Tests pass (`php artisan test`)
- New features include tests
- Documentation is updated

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

See also the list of [contributors](https://github.com/yourusername/sms-project/contributors) who participated in this project.

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- All contributors and supporters

---

## 📞 Support

- **Documentation**: `/docs` folder
- **Issues**: [GitHub Issues](https://github.com/yourusername/sms-project/issues)
- **Email**: support@example.com

---

## 🗺️ Roadmap

- [ ] Mobile application (React Native)
- [ ] Parent portal
- [ ] Online admission system
- [ ] Library management module
- [ ] Transport management module
- [ ] Hostel management module
- [ ] Inventory management
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] API documentation (Swagger)

---

## 📊 Stats

![GitHub stars](https://img.shields.io/github/stars/yourusername/sms-project?style=social)
![GitHub forks](https://img.shields.io/github/forks/yourusername/sms-project?style=social)
![GitHub watchers](https://img.shields.io/github/watchers/yourusername/sms-project?style=social)

---

<div align="center">

**[⬆ back to top](#-school-management-system-sms)**

Made with ❤️ by [Your Name](https://github.com/yourusername)

</div>
