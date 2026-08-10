# 📁 Project Structure

This document provides a complete overview of the SMS Project folder structure.

---

## 🌳 Root Directory

```
SMS_PROJECT/
├── 📄 .editorconfig              # Editor configuration
├── 📄 .env                        # Environment variables (not in git)
├── 📄 .env.example                # Environment template
├── 📁 .git/                       # Git repository
├── 📄 .gitattributes              # Git attributes
├── 📄 .gitignore                  # Git ignore rules
├── 📁 app/                        # Application code
├── 📄 artisan                     # Laravel CLI
├── 📁 bootstrap/                  # Framework bootstrap
├── 📄 CHANGELOG.md                # Version history
├── 📄 composer.json               # PHP dependencies
├── 📄 composer.lock               # Dependency lock file
├── 📁 config/                     # Configuration files
├── 📄 CONTRIBUTING.md             # Contribution guide
├── 📁 database/                   # Database files
├── 📁 docs/                       # Documentation
├── 📄 LICENSE                     # MIT License
├── 📁 node_modules/               # Node dependencies
├── 📄 package.json                # Node dependencies config
├── 📄 phpunit.xml                 # PHPUnit configuration
├── 📁 public/                     # Public web root
├── 📄 README.md                   # Project overview
├── 📁 resources/                  # Views, assets
├── 📁 routes/                     # Route definitions
├── 📁 storage/                    # Generated files
├── 📁 tests/                      # Tests
├── 📁 vendor/                     # Composer packages
└── 📄 vite.config.js              # Vite configuration
```

---

## 📂 Application Directory (`/app`)

```
app/
├── 📁 Console/
│   └── 📁 Commands/               # Artisan commands
│       ├── PromoteStudentsCommand.php
│       ├── GenerateReportCardsCommand.php
│       ├── CleanupInactiveStudentsCommand.php
│       └── SyncStudentStatusCommand.php
│
├── 📁 Events/                     # Event classes
│   ├── StudentRegistered.php
│   ├── StudentPromoted.php
│   └── StudentStatusChanged.php
│
├── 📁 Http/
│   ├── 📁 Controllers/            # HTTP controllers
│   │   ├── StudentController.php
│   │   ├── TeacherController.php
│   │   ├── PaymentController.php
│   │   └── ... (40+ controllers)
│   │
│   ├── 📁 Middleware/             # HTTP middleware
│   │   └── RateLimitMiddleware.php
│   │
│   ├── 📁 Requests/               # Form request validation
│   │   └── 📁 Student/
│   │       ├── StoreStudentRequest.php
│   │       └── UpdateStudentRequest.php
│   │
│   └── 📁 Resources/              # API resources
│       ├── StudentResource.php
│       └── StudentCollection.php
│
├── 📁 Jobs/                       # Queue jobs
│   ├── SendWelcomeEmailJob.php
│   ├── ProcessBulkStudentImport.php
│   └── GenerateReportCardJob.php
│
├── 📁 Listeners/                  # Event listeners
│   ├── SendStudentWelcomeEmail.php
│   ├── LogStudentPromotion.php
│   └── NotifyGuardiansOfStatusChange.php
│
├── 📁 Mail/                       # Email templates
│   ├── StudentWelcomeMail.php
│   ├── TeacherWelcomeMail.php
│   └── GuardianWelcomeMail.php
│
├── 📁 Models/                     # Eloquent models
│   ├── Student.php
│   ├── Teacher.php
│   ├── Payment.php
│   ├── Attendance.php
│   ├── Result.php
│   └── ... (96+ models)
│
├── 📁 Observers/                  # Model observers
│   └── StudentObserver.php
│
├── 📁 Policies/                   # Authorization policies
│   └── StudentPolicy.php
│
├── 📁 Providers/                  # Service providers
│   ├── AppServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RepositoryServiceProvider.php
│
├── 📁 Repositories/               # Data access layer
│   ├── 📁 Contracts/
│   │   └── RepositoryInterface.php
│   ├── BaseRepository.php
│   ├── StudentRepository.php
│   ├── TeacherRepository.php
│   ├── PaymentRepository.php
│   ├── AttendanceRepository.php
│   └── ResultRepository.php
│
└── 📁 Services/                   # Business logic layer
    ├── StudentService.php
    ├── TeacherService.php
    └── PaymentService.php
```

---

## 📁 Documentation (`/docs`)

```
docs/
├── 📄 INDEX.md                    # Documentation index
├── 📄 ARCHITECTURE.md             # Architecture guide
├── 📄 TESTING_GUIDE.md            # Testing guide
├── 📄 IMPLEMENTATION_COMPLETE.md # Implementation summary
├── 📄 EMAIL_DELIVERABILITY_GUIDE.md
├── 📄 MOBILE_RESPONSIVE_BUTTONS_GUIDE.md
│
├── 📁 guides/                     # User guides
├── 📁 setup/                      # Setup guides
└── 📁 technical/                  # Technical docs
```

---

## 🧪 Tests Directory (`/tests`)

```
tests/
├── 📁 Feature/                    # Feature tests
│   └── 📁 Student/
│       └── StudentEnrollmentTest.php
│
├── 📁 Unit/                       # Unit tests
│   └── 📁 Services/
│       └── StudentServiceTest.php
│
└── 📄 TestCase.php                # Base test class
```

---

## 🗄️ Database Directory (`/database`)

```
database/
├── 📁 factories/                  # Model factories
├── 📁 migrations/                 # Database migrations
└── 📁 seeders/                    # Database seeders
```

---

## 🎨 Resources Directory (`/resources`)

```
resources/
├── 📁 css/                        # Stylesheets
├── 📁 js/                         # JavaScript
└── 📁 views/                      # Blade templates
    ├── 📁 students/
    ├── 📁 teachers/
    ├── 📁 payments/
    └── ... (organized by module)
```

---

## 🌐 Public Directory (`/public`)

```
public/
├── 📄 index.php                   # Entry point
├── 📁 build/                      # Compiled assets
├── 📁 storage/                    # Public storage link
└── 📁 vendor/                     # Public vendor files
```

---

## 🛣️ Routes Directory (`/routes`)

```
routes/
├── 📄 web.php                     # Web routes
└── 📄 console.php                 # Console routes
```

---

## ⚙️ Configuration Directory (`/config`)

```
config/
├── 📄 app.php                     # Application config
├── 📄 auth.php                    # Authentication config
├── 📄 database.php                # Database config
├── 📄 mail.php                    # Mail config
├── 📄 queue.php                   # Queue config
└── ... (Laravel config files)
```

---

## 💾 Storage Directory (`/storage`)

```
storage/
├── 📁 app/                        # Application storage
│   ├── 📁 public/                 # Public files
│   └── 📁 private/                # Private files
│
├── 📁 framework/                  # Framework files
│   ├── 📁 cache/
│   ├── 📁 sessions/
│   └── 📁 views/
│
└── 📁 logs/                       # Log files
    └── laravel.log
```

---

## 📊 Key Directories by Purpose

### 🏗️ Architecture Patterns

| Pattern | Location |
|---------|----------|
| **Controllers** | `/app/Http/Controllers/` |
| **Services** | `/app/Services/` |
| **Repositories** | `/app/Repositories/` |
| **Policies** | `/app/Policies/` |
| **Observers** | `/app/Observers/` |
| **Events** | `/app/Events/` |
| **Listeners** | `/app/Listeners/` |
| **Jobs** | `/app/Jobs/` |
| **Commands** | `/app/Console/Commands/` |
| **Form Requests** | `/app/Http/Requests/` |
| **API Resources** | `/app/Http/Resources/` |
| **Middleware** | `/app/Http/Middleware/` |

### 🧪 Testing

| Type | Location |
|------|----------|
| **Unit Tests** | `/tests/Unit/` |
| **Feature Tests** | `/tests/Feature/` |
| **Test Config** | `/phpunit.xml` |

### 📚 Documentation

| Document | Location |
|----------|----------|
| **Main README** | `/README.md` |
| **Architecture** | `/docs/ARCHITECTURE.md` |
| **Testing** | `/docs/TESTING_GUIDE.md` |
| **Contributing** | `/CONTRIBUTING.md` |
| **Changelog** | `/CHANGELOG.md` |
| **License** | `/LICENSE` |

---

## 🎯 Navigation Guide

### For New Developers

**Start Here:**
1. `/README.md` - Project overview
2. `/docs/ARCHITECTURE.md` - Understand architecture
3. `/docs/TESTING_GUIDE.md` - Learn testing
4. `/app/` - Explore codebase

**Code Organization:**
- Controllers → `/app/Http/Controllers/`
- Business Logic → `/app/Services/`
- Data Access → `/app/Repositories/`
- Models → `/app/Models/`

### For Contributing

**Before Making Changes:**
1. Read `/CONTRIBUTING.md`
2. Check `/docs/ARCHITECTURE.md` for patterns
3. Look at existing code in `/app/`
4. Write tests in `/tests/`

### For Deployment

**Required Files:**
1. `.env` (copy from `.env.example`)
2. `composer.json` (dependencies)
3. `package.json` (frontend assets)
4. `/database/migrations/` (database schema)

---

## 📏 Naming Conventions

### Files
- Controllers: `StudentController.php`
- Models: `Student.php` (singular)
- Services: `StudentService.php`
- Repositories: `StudentRepository.php`
- Policies: `StudentPolicy.php`
- Observers: `StudentObserver.php`
- Jobs: `SendWelcomeEmailJob.php`
- Commands: `PromoteStudentsCommand.php`
- Form Requests: `StoreStudentRequest.php`

### Directories
- Plural for collections: `Controllers/`, `Models/`
- Singular for modules: `Student/`, `Teacher/`
- PascalCase: `Http/`, `Console/`

---

## 🔍 Finding Code

### By Feature

**Student Management:**
- Controller: `/app/Http/Controllers/StudentController.php`
- Service: `/app/Services/StudentService.php`
- Repository: `/app/Repositories/StudentRepository.php`
- Model: `/app/Models/Student.php`
- Tests: `/tests/Feature/Student/`

**Payment Processing:**
- Controller: `/app/Http/Controllers/PaymentController.php`
- Service: `/app/Services/PaymentService.php`
- Repository: `/app/Repositories/PaymentRepository.php`
- Model: `/app/Models/Payment.php`

### By Pattern

**Need to add validation?**
→ Create in `/app/Http/Requests/`

**Need business logic?**
→ Add to `/app/Services/`

**Need database query?**
→ Add to `/app/Repositories/`

**Need authorization?**
→ Add to `/app/Policies/`

**Need background job?**
→ Create in `/app/Jobs/`

---

## 📦 File Count Statistics

| Directory | File Count |
|-----------|------------|
| Controllers | 40+ |
| Models | 96+ |
| Repositories | 5 |
| Services | 3 |
| Policies | 1 |
| Observers | 1 |
| Events | 3 |
| Listeners | 3 |
| Jobs | 3 |
| Commands | 4 |
| Form Requests | 2 |
| API Resources | 2 |
| Tests | 2+ |

**Total New Files (v2.0):** 40+ files added

---

## 🎉 Summary

This project follows a **clean, organized, professional structure** with:

✅ Clear separation of concerns
✅ Consistent naming conventions
✅ Comprehensive documentation
✅ Well-organized tests
✅ Professional configuration
✅ Enterprise-grade architecture

---

<div align="center">

**[⬆ Back to README](README.md)** | **[📚 Documentation](docs/INDEX.md)**

Last Updated: 2026-07-29

</div>
