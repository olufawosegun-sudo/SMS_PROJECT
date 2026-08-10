<laravel-boost-guidelines>
=== .ai/sms-project-patterns rules ===

# SMS Project Architecture Patterns

## Repository Pattern

All data access MUST go through repositories.

### Structure

```php
app/Repositories/
├── Contracts/
│   └── RepositoryInterface.php
├── BaseRepository.php
└── {Model}Repository.php
```

### Example Repository

```php
class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }
    
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->latest()->paginate($perPage);
    }
}
```

## Service Layer Pattern

All business logic MUST be in services.

### Structure

```php
app/Services/{Model}Service.php
```

### Example Service

```php
class StudentService
{
    protected $studentRepository;
    
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }
    
    public function createStudent(array $data, int $schoolId)
    {
        return DB::transaction(function () use ($data, $schoolId) {
            // Business logic here
            $student = $this->studentRepository->create($data);
            event(new StudentRegistered($student));
            return $student;
        });
    }
}
```

## Controller Pattern

Controllers MUST be thin - only handle HTTP.

### Structure

```php
public function __construct(StudentService $studentService)
{
    $this->studentService = $studentService;
}

public function store(StoreStudentRequest $request)
{
    $this->authorize('create', Student::class);
    
    $student = $this->studentService->createStudent(
        $request->validated(),
        Auth::user()->school->id
    );
    
    return redirect()->route('students.index')
        ->with('success', 'Student created!');
}
```

## Form Request Pattern

All validation MUST use Form Requests.

### Structure

```php
app/Http/Requests/{Model}/{Action}Request.php
```

### Example

```php
class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }
    
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
```

## Policy Pattern

All authorization MUST use Policies.

### Structure

```php
app/Policies/{Model}Policy.php
```

### Example

```php
class StudentPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role->name, ['Principal', 'Admin']);
    }
    
    public function update(User $user, Student $student): bool
    {
        return $user->school_id === $student->school_id 
            && in_array($user->role->name, ['Principal', 'Admin']);
    }
}
```

## Observer Pattern

Use Observers for model lifecycle events.

### Structure

```php
app/Observers/{Model}Observer.php
```

### Example

```php
class StudentObserver
{
    public function creating(Student $student): void
    {
        if (empty($student->uuid)) {
            $student->uuid = (string) Str::uuid();
        }
    }
    
    public function created(Student $student): void
    {
        ActivityLog::create([
            'action' => 'created',
            'subject_type' => Student::class,
            'subject_id' => $student->id,
        ]);
    }
}
```

## Event/Listener Pattern

Use Events for decoupled side effects.

### Structure

```php
app/Events/{Event}.php
app/Listeners/{Listener}.php
```

### Example

```php
// Event
class StudentRegistered
{
    public $student;
    public $password;
    
    public function __construct(Student $student, string $password)
    {
        $this->student = $student;
        $this->password = $password;
    }
}

// Listener
class SendStudentWelcomeEmail implements ShouldQueue
{
    public function handle(StudentRegistered $event): void
    {
        SendWelcomeEmailJob::dispatch($event->student, $event->password);
    }
}
```

## Job Pattern

Use Jobs for async tasks.

### Structure

```php
app/Jobs/{Job}Job.php
```

### Example

```php
class SendWelcomeEmailJob implements ShouldQueue
{
    public $tries = 3;
    public $backoff = [30, 60, 120];
    
    protected $student;
    protected $password;
    
    public function handle(): void
    {
        Mail::to($this->student->user->email)
            ->send(new StudentWelcomeMail($this->student, $this->password));
    }
}
```

## API Resource Pattern

Use API Resources for consistent responses.

### Structure

```php
app/Http/Resources/{Model}Resource.php
```

### Example

```php
class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_no' => $this->admission_no,
            'full_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'class' => [
                'id' => $this->schoolClass->id,
                'name' => $this->schoolClass->name,
            ],
        ];
    }
}
```

## School Isolation

ALWAYS ensure school-level data isolation.

### In Repositories

```php
public function getBySchool(int $schoolId)
{
    return $this->model->where('school_id', $schoolId)->get();
}
```

### In Policies

```php
public function view(User $user, Student $student): bool
{
    return $user->school_id === $student->school_id;
}
```

### In Services

```php
public function findStudent(int $studentId, int $schoolId)
{
    $student = $this->studentRepository->find($studentId);
    
    if ($student && $student->school_id !== $schoolId) {
        throw new \Exception('Unauthorized access.');
    }
    
    return $student;
}
```

## Naming Conventions

### Files

- Controllers: `StudentController.php`
- Services: `StudentService.php`
- Repositories: `StudentRepository.php`
- Models: `Student.php` (singular)
- Policies: `StudentPolicy.php`
- Form Requests: `StoreStudentRequest.php`, `UpdateStudentRequest.php`

### Methods

- camelCase: `getStudentsByClass()`
- Descriptive: `createStudentWithUserAccount()`

### Variables

- camelCase: `$studentData`
- Descriptive: `$admissionNumber`

## Testing Requirements

### Unit Tests

Test services with mocked repositories.

### Feature Tests

Test complete workflows end-to-end.

### Test Structure

```php
/** @test */
public function it_can_create_student_with_valid_data()
{
    // Arrange
    $mockRepo = Mockery::mock(StudentRepository::class);
    
    // Act
    $result = $service->createStudent($data);
    
    // Assert
    $this->assertInstanceOf(Student::class, $result);
}
```

## Error Handling

Always use try-catch in services:

```php
public function createStudent(array $data, int $schoolId)
{
    return DB::transaction(function () use ($data, $schoolId) {
        try {
            // Business logic
        } catch (\Exception $e) {
            Log::error('Student creation failed', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    });
}
```

## Documentation

Add PHPDoc to all public methods:

```php
/**
 * Create a new student with user account.
 *
 * @param array $data Student data
 * @param int $schoolId School ID
 * @return Student
 * @throws \Exception
 */
public function createStudent(array $data, int $schoolId): Student
```

## SOLID Principles

- **Single Responsibility**: One class, one purpose
- **Open/Closed**: Extend, don't modify
- **Liskov Substitution**: Repositories are interchangeable
- **Interface Segregation**: Focused interfaces
- **Dependency Inversion**: Depend on abstractions

## Key Rules

1. ✅ Controllers MUST be thin
2. ✅ Services contain business logic
3. ✅ Repositories handle data access
4. ✅ Policies handle authorization
5. ✅ Form Requests handle validation
6. ✅ Observers for model lifecycle
7. ✅ Events for decoupled actions
8. ✅ Jobs for async tasks
9. ✅ Always ensure school isolation
10. ✅ Write tests for new features

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3
- laravel/framework (LARAVEL) - v12
- laravel/nightwatch (NIGHTWATCH) - v1
- laravel/prompts (PROMPTS) - v0
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app/Console/Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
