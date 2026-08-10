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
