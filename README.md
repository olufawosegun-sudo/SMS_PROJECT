# 🎓 EduWest Africa - School Management System

A comprehensive School Management System built with Laravel 11, designed specifically for West African secondary schools.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)
![License](https://img.shields.io/badge/license-Proprietary-green.svg)

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [User Roles](#user-roles)
- [Key Modules](#key-modules)
- [Documentation](#documentation)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [Support](#support)

---

## 🌟 Overview

EduWest Africa is a fully-featured, multi-tenant School Management System designed to streamline administrative, academic, and financial operations for West African secondary schools. The system supports multiple user roles with granular permissions and provides comprehensive tools for student management, staff management, academics, finance, and communication.

---

## ✨ Features

### 👥 **User Management**
- Multi-role access control (Owner, Principal, Teacher, Guardian, Student)
- Role-based dashboards with customized views
- Secure authentication and authorization
- User activity tracking and audit logs

### 🎓 **Student Management**
- Complete student lifecycle management (admission to alumni)
- Student documents management with expiry tracking
- Student attendance tracking with reporting
- Student promotions and transfers
- Academic performance monitoring
- Student medical records and emergency contacts

### 👨‍🏫 **Staff Management**
- Teacher, Principal, and Administrative staff management
- Staff attendance and leave management
- Staff payroll processing
- Department and subject assignments
- Performance tracking

### 📚 **Academic Management**
- Academic sessions and terms management
- Class and class arms organization
- Subject management with teacher assignments
- Timetable scheduling
- Continuous assessments and CBT exams
- Results processing and report cards
- Grading systems with customizable scales

### 💰 **Financial Management** (Owner Only)
- Fee categories and structures
- Invoice generation and management
- Payment tracking and receipts
- Expense management
- Financial reports and analytics
- Staff payroll integration

### 📨 **Communication**
- Announcements system
- Internal messaging
- SMS notifications
- Email notifications
- Welcome emails for new users

### 🔐 **Admissions**
- Public admission application portal
- Document upload during application
- Application review and processing
- Admission offer letters
- Automatic student enrollment

### 🎯 **Additional Features**
- Database backup and restore
- Real-time notifications
- Attendance analytics
- Multi-school support (multi-tenant)
- Responsive design (mobile-friendly)
- Dark mode support

---

## 💻 System Requirements

- **PHP:** >= 8.2
- **MySQL:** >= 8.0
- **Composer:** Latest version
- **Node.js:** >= 18.x (for frontend assets)
- **NPM:** Latest version
- **Web Server:** Apache/Nginx

---

## 🚀 Installation

### 1. Clone the Repository
```bash
cd C:\xampp\htdocs
git clone <repository-url> SMS_Project
cd SMS_Project
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install JavaScript Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Configuration
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sms_project
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Seed Database (Optional)
```bash
php artisan db:seed
```

### 8. Create Storage Link
```bash
php artisan storage:link
```

### 9. Build Frontend Assets
```bash
npm run build
# Or for development
npm run dev
```

### 10. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 👤 User Roles

| Role | Description | Key Permissions |
|------|-------------|-----------------|
| **Owner** | School owner/administrator | Full system access including financial management |
| **Principal** | School principal/head | Academic management, staff oversight (no financial access) |
| **Teacher** | Teaching staff | Class management, student grades, attendance |
| **Guardian** | Parent/guardian | View their child's information, payments, reports |
| **Student** | Enrolled students | View personal information, results, timetable |

---

## 📦 Key Modules

### 1. **Student Management**
- Student registration and profile management
- Document management (birth certificates, medical records, etc.)
- Attendance tracking with reports
- Promotion and transfer handling
- Alumni management

### 2. **Staff Management**
- Teacher and principal management
- Staff attendance tracking
- Payroll processing
- Department assignments

### 3. **Academic Management**
- Session and term management
- Class and subject configuration
- Timetable scheduling
- Assessment and examination
- Results and report cards

### 4. **Financial Management**
- Fee structure setup
- Invoice generation
- Payment processing
- Expense tracking
- Financial reports

### 5. **Admissions**
- Public application portal at `/apply`
- Document upload support
- Application review workflow
- Admission offer generation

### 6. **Communication**
- Announcements
- Messaging system
- SMS integration
- Email notifications

---

## 📚 Documentation

All documentation is organized in the `/docs` folder:

### Technical Documentation (`/docs/technical`)
- Database ERD and relationships
- Database normalization guide
- Table structure documentation

### Implementation Guides (`/docs/guides`)
- Admission system guide
- Dashboard implementation
- Feature-specific guides
- Migration guides

### Setup Instructions (`/docs/setup`)
- Installation instructions
- Testing guidelines
- Configuration guides

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum/Session

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Vanilla JS + Alpine.js
- **Build Tool:** Vite
- **Icons:** Heroicons

### Additional Tools
- **Mail:** Laravel Mail (SMTP support)
- **Queue:** Database/Redis (configurable)
- **Cache:** File/Redis (configurable)
- **File Storage:** Local/Cloud storage

---

## 📁 Project Structure

```
SMS_Project/
├── app/                    # Application code
│   ├── Http/
│   │   └── Controllers/   # All controllers
│   ├── Models/            # Eloquent models
│   ├── Mail/              # Mailable classes
│   └── Providers/         # Service providers
├── bootstrap/             # Framework bootstrap
├── config/                # Configuration files
├── database/              # Migrations, seeders, factories
│   └── migrations/        # Database migrations
├── docs/                  # Project documentation
│   ├── technical/         # Technical docs
│   ├── guides/            # Implementation guides
│   └── setup/             # Setup instructions
├── public/                # Public assets
│   ├── css/              # Compiled CSS
│   ├── js/               # Compiled JavaScript
│   └── images/           # Static images
├── resources/             # Views and raw assets
│   ├── views/            # Blade templates
│   ├── css/              # Source CSS
│   └── js/               # Source JavaScript
├── routes/                # Route definitions
│   ├── web.php           # Web routes
│   └── api.php           # API routes
├── storage/               # Application storage
│   ├── app/              # Application files
│   ├── framework/        # Framework files
│   └── logs/             # Application logs
├── tests/                 # Automated tests
└── vendor/                # Composer dependencies
```

---

## 🤝 Contributing

This is a proprietary project. For contribution guidelines, please contact the development team.

---

## 📞 Support

For technical support or inquiries:
- **Email:** support@eduwestafrica.com
- **Documentation:** See `/docs` folder
- **Issues:** Contact development team

---

## 📄 License

This software is proprietary and confidential. Unauthorized copying, distribution, or modification is strictly prohibited.

---

## 🎯 Version History

### Version 1.0.0 (Current)
- ✅ Complete student management system
- ✅ Staff management with payroll
- ✅ Academic management with results
- ✅ Financial management (Owner only)
- ✅ Admission portal with document upload
- ✅ Multi-role dashboards
- ✅ Communication system
- ✅ Database backup and restore

---

## 🙏 Acknowledgments

Built with ❤️ for West African schools.

**EduWest Africa** - Empowering Education Through Technology

---

**Last Updated:** 2026-07-19
