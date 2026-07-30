# Testing Guide - SMS Project

## 📋 Overview
This guide explains how to write and run tests for the SMS Project.

---

## 🛠️ Setup

### 1. Install PHPUnit (if not installed)
```bash
composer require --dev phpunit/phpunit
```

### 2. Install Mockery (for mocking)
```bash
composer require --dev mockery/mockery
```

### 3. Configure Test Database
Update your `phpunit.xml`:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="CACHE_DRIVER" value="array"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
    <env name="MAIL_MAILER" value="array"/>
</php>
```

---

## 🧪 Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test File
```bash
php artisan test tests/Unit/Services/StudentServiceTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter it_can_get_school_students
```

### Run with Coverage
```bash
php artisan test --coverage
```

### Run Only Unit Tests
```bash
php artisan test tests/Unit
```

### Run Only Feature Tests
```bash
php artisan test tests/Feature
```

---

## 📝 Writing Unit Tests

### Example: Testing a Service

```php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\StudentService;
use App\Repositories\StudentRepository;
use Mockery;

class StudentServiceTest extends TestCase
{
    protected $studentService;
    protected $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mock
        $this->mockRepository = Mockery::mock(StudentRepository::class);
        
        // Inject into service
        $this->studentService = new StudentService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_do_something()
    {
        // Arrange - Set up test data and expectations
        $this->mockRepository
            ->shouldReceive('someMethod')
            ->once()
            ->with('expected', 'parameters')
            ->andReturn('expected result');

        // Act - Call the method being tested
        $result = $this->studentService->someMethod('expected', 'parameters');

        // Assert - Verify the result
        $this->assertEquals('expected result', $result);
    }
}
```

### Key Concepts:

1. **Mock Dependencies**: Mock external dependencies (repositories, APIs)
2. **Arrange-Act-Assert**: Structure tests clearly
3. **One Assertion**: Test one thing per test method
4. **Descriptive Names**: Use clear test method names

---

## 🎯 Writing Feature Tests

### Example: Testing Complete Workflow

```php
<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;

class StudentEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function principal_can_enroll_new_student()
    {
        // Arrange
        Mail::fake();
        Event::fake();
        
        $principal = User::factory()->create(['role' => 'Principal']);
        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            // ...
        ];

        // Act
        $response = $this->actingAs($principal)
            ->post(route('students.store'), $studentData);

        // Assert
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['first_name' => 'John']);
        Mail::assertQueued(StudentWelcomeMail::class);
        Event::assertDispatched(StudentRegistered::class);
    }
}
```

### Key Concepts:

1. **RefreshDatabase**: Reset database between tests
2. **Factories**: Use factories to create test data
3. **Fake Facades**: Use `Mail::fake()`, `Event::fake()` for testing
4. **actingAs()**: Simulate authenticated user
5. **assertDatabase**: Check database state

---

## 🏗️ Test Structure

```
tests/
├── Feature/              # End-to-end tests
│   ├── Student/
│   │   ├── StudentEnrollmentTest.php
│   │   ├── StudentUpdateTest.php
│   │   └── StudentSearchTest.php
│   ├── Teacher/
│   └── Payment/
│
├── Unit/                 # Isolated component tests
│   ├── Services/
│   │   ├── StudentServiceTest.php
│   │   ├── TeacherServiceTest.php
│   │   └── PaymentServiceTest.php
│   ├── Repositories/
│   │   ├── StudentRepositoryTest.php
│   │   └── PaymentRepositoryTest.php
│   └── Policies/
│       └── StudentPolicyTest.php
│
└── TestCase.php          # Base test class
```

---

## 📊 Testing Best Practices

### 1. Test Naming Convention
```php
// ✅ Good - Describes what it tests
public function it_creates_student_with_valid_data()
public function it_throws_exception_when_email_is_duplicate()

// ❌ Bad - Unclear what it tests
public function test1()
public function testStudent()
```

### 2. Arrange-Act-Assert Pattern
```php
public function it_calculates_total_correctly()
{
    // Arrange - Set up test data
    $payment1 = 100;
    $payment2 = 200;
    
    // Act - Execute the code
    $total = $this->service->calculateTotal($payment1, $payment2);
    
    // Assert - Verify the result
    $this->assertEquals(300, $total);
}
```

### 3. Test One Thing
```php
// ✅ Good - Tests one behavior
public function it_validates_required_fields()
{
    $response = $this->post('/students', []);
    $response->assertSessionHasErrors(['first_name', 'last_name']);
}

// ❌ Bad - Tests multiple behaviors
public function it_does_everything()
{
    // Create student
    // Update student
    // Delete student
    // Check email sent
    // Check database
}
```

### 4. Use Factories
```php
// ✅ Good - Use factories
$student = Student::factory()->create(['status' => 'active']);

// ❌ Bad - Manual creation
$student = Student::create([
    'uuid' => Str::uuid(),
    'school_id' => 1,
    'user_id' => 1,
    'admission_no' => 'STU001',
    // 10+ more fields...
]);
```

### 5. Mock External Services
```php
// ✅ Good - Mock external API
Http::fake([
    'api.paymentgateway.com/*' => Http::response(['status' => 'success'])
]);

// ❌ Bad - Call real API in tests
$response = Http::post('api.paymentgateway.com/pay', $data);
```

---

## 🔍 Common Assertions

### Database Assertions
```php
$this->assertDatabaseHas('students', ['admission_no' => 'STU001']);
$this->assertDatabaseMissing('students', ['admission_no' => 'STU999']);
$this->assertSoftDeleted('students', ['id' => 1]);
```

### Response Assertions
```php
$response->assertStatus(200);
$response->assertRedirect(route('students.index'));
$response->assertSessionHas('success');
$response->assertSessionHasErrors(['email']);
$response->assertForbidden();
```

### Mail Assertions
```php
Mail::fake();
Mail::assertSent(StudentWelcomeMail::class);
Mail::assertQueued(StudentWelcomeMail::class);
Mail::assertNotSent(SomeOtherMail::class);
```

### Event Assertions
```php
Event::fake();
Event::assertDispatched(StudentRegistered::class);
Event::assertNotDispatched(SomeOtherEvent::class);
```

---

## 🎯 What to Test

### ✅ DO Test:
- Business logic in services
- Validation rules
- Authorization policies
- Database operations
- API endpoints
- Critical user workflows
- Edge cases and error handling

### ❌ DON'T Test:
- Framework features (Laravel already tests these)
- Third-party packages
- Getters/setters without logic
- Database migrations (test with feature tests instead)

---

## 📈 Test Coverage Goals

| Component | Target Coverage |
|-----------|----------------|
| Services | 90%+ |
| Repositories | 80%+ |
| Controllers | 70%+ |
| Policies | 90%+ |
| Overall | 75%+ |

---

## 🐛 Debugging Tests

### 1. Show Test Output
```bash
php artisan test --verbose
```

### 2. Stop on Failure
```bash
php artisan test --stop-on-failure
```

### 3. Use dd() in Tests
```php
public function it_does_something()
{
    $result = $this->service->doSomething();
    dd($result); // Dump and die to inspect
}
```

### 4. Check Database State
```php
$this->dumpTable('students');
$this->dumpHeaders();
$this->dumpSession();
```

---

## 🚀 Continuous Integration

Add to `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: php artisan test --coverage
```

---

## 📚 Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Mockery Documentation](http://docs.mockery.io/)

---

## ✅ Checklist Before Deployment

- [ ] All tests pass
- [ ] Test coverage > 75%
- [ ] Feature tests for critical workflows
- [ ] Unit tests for complex logic
- [ ] Edge cases covered
- [ ] Error handling tested
- [ ] Authorization tested
- [ ] No skipped/ignored tests without reason

---

Last Updated: 2026-07-29
