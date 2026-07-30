# SMS Project - Architecture Documentation

## Overview
This project now follows modern Laravel architectural patterns for better code organization, maintainability, and testability.

---

## Design Patterns Implemented

### 1. Repository Pattern
**Location**: `app/Repositories/`

The Repository pattern abstracts data access logic from business logic.

**Structure**:
- `Contracts/RepositoryInterface.php` - Base interface defining standard CRUD operations
- `BaseRepository.php` - Abstract base class implementing common repository methods
- `StudentRepository.php` - Student-specific data access methods

**Usage**:
```php
// In a service or controller
$students = $this->studentRepository->getBySchool($schoolId, 20);
$student = $this->studentRepository->findByAdmissionNo('STU2024001');
```

**Benefits**:
- Separates data access from business logic
- Makes testing easier (can mock repositories)
- Provides a consistent interface for data operations
- Easy to switch data sources (database, API, cache)

---

### 2. Service Pattern
**Location**: `app/Services/`

The Service layer contains business logic and coordinates between repositories, events, and other services.

**Structure**:
- `StudentService.php` - Handles student-related business operations

**Usage**:
```php
// In a controller
public function __construct(StudentService $studentService)
{
    $this->studentService = $studentService;
}

public function store(StoreStudentRequest $request)
{
    $student = $this->studentService->createStudent(
        $request->validated(),
        $school->id
    );
}
```

**Benefits**:
- Keeps controllers thin and focused
- Business logic is reusable across controllers, commands, jobs
- Easier to test business logic in isolation
- Maintains transaction boundaries

---

### 3. Form Request Pattern
**Location**: `app/Http/Requests/Student/`

Form Requests handle validation logic, keeping it out of controllers.

**Structure**:
- `StoreStudentRequest.php` - Validation rules for creating students
- `UpdateStudentRequest.php` - Validation rules for updating students

**Features**:
- Authorization checks
- Custom validation rules
- Custom error messages
- Data preparation before validation

**Usage**:
```php
public function store(StoreStudentRequest $request)
{
    // $request->validated() contains only validated data
    $data = $request->validated();
}
```

**Benefits**:
- Keeps validation logic organized and reusable
- Automatic validation before controller method runs
- Custom error messages in one place
- Type hinting provides IDE support

---

### 4. Policy Pattern
**Location**: `app/Policies/`

Policies centralize authorization logic for model operations.

**Structure**:
- `StudentPolicy.php` - Authorization rules for Student operations

**Methods**:
- `viewAny()` - Can user view student list?
- `view()` - Can user view specific student?
- `create()` - Can user create students?
- `update()` - Can user update this student?
- `delete()` - Can user delete this student?
- `promote()` - Can user promote students?
- Custom methods for specific actions

**Usage**:
```php
// In controller
$this->authorize('view', $student);
$this->authorize('create', Student::class);

// In Blade views
@can('update', $student)
    <a href="{{ route('students.edit', $student) }}">Edit</a>
@endcan
```

**Benefits**:
- Centralized authorization logic
- Role-based access control
- School-level data isolation
- Clean, readable authorization checks

---

### 5. Observer Pattern
**Location**: `app/Observers/`

Observers listen to model lifecycle events and perform actions automatically.

**Structure**:
- `StudentObserver.php` - Handles Student model events

**Events**:
- `creating` - Before student is created
- `created` - After student is created
- `updating` - Before student is updated
- `updated` - After student is updated
- `deleting` - Before student is deleted
- `deleted` - After student is deleted
- `restored` - After soft-deleted student is restored

**Automatic Actions**:
- Generate UUIDs
- Log activity for audit trails
- Track status changes
- Log class changes (promotions)

**Benefits**:
- Automatic logging without explicit calls
- Consistent behavior across all CRUD operations
- Audit trail generation
- Clean separation of concerns

---

### 6. Event/Listener Pattern
**Location**: `app/Events/` and `app/Listeners/`

Events and Listeners provide a decoupled way to handle side effects of actions.

**Events**:
- `StudentRegistered` - Fired when a new student is created
- `StudentPromoted` - Fired when a student is promoted to new class
- `StudentStatusChanged` - Fired when student status changes

**Listeners**:
- `SendStudentWelcomeEmail` - Sends welcome email (queued)
- `LogStudentPromotion` - Logs promotion activity
- `NotifyGuardiansOfStatusChange` - Notifies guardians (queued)

**Usage**:
```php
// In service
event(new StudentRegistered($student, $password));
```

**Benefits**:
- Decoupled components
- Multiple listeners for single event
- Async processing with queues
- Easy to add new listeners without modifying existing code

---

### 7. Job Pattern
**Location**: `app/Jobs/`

Jobs handle long-running or async tasks that should run in the background.

**Jobs**:
- `SendWelcomeEmailJob` - Sends welcome emails with retry logic
- `ProcessBulkStudentImport` - Imports students from CSV
- `GenerateReportCardJob` - Generates student report cards

**Features**:
- Queue support for async processing
- Automatic retry on failure
- Timeout handling
- Failed job handling

**Usage**:
```php
// Dispatch to queue
SendWelcomeEmailJob::dispatch($student, $password, 'student');

// Dispatch and wait (sync)
SendWelcomeEmailJob::dispatchSync($student, $password, 'student');
```

**Benefits**:
- Non-blocking operations
- Better user experience (faster response times)
- Automatic retry on failure
- Better resource management

---

### 8. Command Pattern
**Location**: `app/Console/Commands/`

Artisan commands for batch operations and maintenance tasks.

**Commands**:
- `students:promote` - Bulk promote students to next class
- `reports:generate` - Generate report cards for students
- `students:cleanup` - Cleanup inactive students
- `students:sync-status` - Sync student/user status mismatches

**Features**:
- Progress bars
- Confirmation prompts
- Dry-run mode
- Detailed output

**Usage**:
```bash
# Promote students
php artisan students:promote 1 10 11 --dry-run

# Generate reports
php artisan reports:generate 1 1 1 --queue

# Cleanup inactive students
php artisan students:cleanup 1 --days=365 --dry-run

# Sync statuses
php artisan students:sync-status 1 --fix-mismatches
```

**Benefits**:
- Automation of repetitive tasks
- Safe batch operations with dry-run
- Can be scheduled with Laravel Scheduler
- Clear output for monitoring

---

## Service Provider Registration

### AppServiceProvider
**Location**: `app/Providers/AppServiceProvider.php`

Registers:
- Model Observers (StudentObserver)
- Policies (StudentPolicy)

### EventServiceProvider
**Location**: `app/Providers/EventServiceProvider.php`

Registers:
- Event to Listener mappings
- StudentRegistered → SendStudentWelcomeEmail
- StudentPromoted → LogStudentPromotion
- StudentStatusChanged → NotifyGuardiansOfStatusChange

### RepositoryServiceProvider
**Location**: `app/Providers/RepositoryServiceProvider.php`

Registers:
- Repository interface bindings
- Singleton repository instances
- StudentRepository

---

## Data Flow

### Creating a Student (Example)

1. **Request** → `StoreStudentRequest` validates input
2. **Controller** → Checks authorization via `StudentPolicy`
3. **Controller** → Calls `StudentService::createStudent()`
4. **Service** → Uses `StudentRepository` to create student in database
5. **Observer** → `StudentObserver::creating()` sets UUID
6. **Observer** → `StudentObserver::created()` logs creation
7. **Event** → `StudentRegistered` event is fired
8. **Listener** → `SendStudentWelcomeEmail` dispatches `SendWelcomeEmailJob`
9. **Job** → Email is sent asynchronously via queue
10. **Response** → Success message returned to user

---

## Testing Strategy

### Unit Tests
- Test Services in isolation (mock repositories)
- Test Repositories with in-memory database
- Test Policies with sample users
- Test Jobs independently

### Feature Tests
- Test Controllers with full stack
- Test Event/Listener integration
- Test Form Request validation
- Test Observer behavior

### Command Tests
- Test Artisan commands
- Verify dry-run mode
- Check output formatting

---

## Adding New Patterns for Other Models

### For Teacher, Payment, or other models:

1. **Create Repository**:
   ```php
   php artisan make:class Repositories/TeacherRepository
   ```
   Extend `BaseRepository` and add specific methods

2. **Create Service**:
   ```php
   php artisan make:class Services/TeacherService
   ```
   Add business logic methods

3. **Create Form Requests**:
   ```php
   php artisan make:request Teacher/StoreTeacherRequest
   php artisan make:request Teacher/UpdateTeacherRequest
   ```

4. **Create Policy**:
   ```php
   php artisan make:policy TeacherPolicy --model=Teacher
   ```

5. **Create Observer**:
   ```php
   php artisan make:observer TeacherObserver --model=Teacher
   ```

6. **Register in Providers**:
   - Add repository to `RepositoryServiceProvider`
   - Add policy to `AppServiceProvider`
   - Add observer to `AppServiceProvider`

---

## Queue Configuration

To run jobs asynchronously, you need to:

1. **Configure Queue Driver** in `.env`:
   ```
   QUEUE_CONNECTION=database
   ```

2. **Create Jobs Table**:
   ```bash
   php artisan queue:table
   php artisan migrate
   ```

3. **Run Queue Worker**:
   ```bash
   php artisan queue:work
   ```

4. **Or use Supervisor** (production):
   Configure supervisor to keep queue worker running

---

## Best Practices

1. **Controllers** should be thin - only handle HTTP requests/responses
2. **Services** contain business logic and orchestration
3. **Repositories** handle data access only
4. **Policies** centralize all authorization
5. **Events** for decoupled side effects
6. **Jobs** for async/long-running tasks
7. **Commands** for maintenance and batch operations

---

## Performance Considerations

- Repositories use eager loading to prevent N+1 queries
- Jobs run asynchronously to avoid blocking requests
- Observer logs are async when possible
- Bulk operations use transactions
- Commands support dry-run for safety

---

## Security Features

- Policy checks prevent unauthorized access
- School-level data isolation in repositories
- Audit logging via observers
- Form request validation prevents invalid data
- Authorization checks at multiple layers

---

## Maintainability Benefits

✅ **Separation of Concerns** - Each class has one responsibility
✅ **Testability** - Easy to write unit and integration tests
✅ **Reusability** - Services can be used anywhere
✅ **Scalability** - Async jobs handle high load
✅ **Documentation** - Clear structure makes code self-documenting
✅ **Team Collaboration** - Patterns provide consistent approach
✅ **Debugging** - Clear data flow makes issues easy to trace

---

## Future Enhancements

Consider adding:
- API Resources for transforming model data
- DTOs (Data Transfer Objects) for complex data structures
- Traits for shared functionality
- Middleware for request filtering
- Custom Collections for model collections
- Specification Pattern for complex queries
- Factory Pattern for object creation

---

Last Updated: 2026-07-29
