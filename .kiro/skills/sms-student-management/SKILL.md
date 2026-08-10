---
name: sms-student-management
description: Work with student enrollment, management, and related features in the SMS system
---

# SMS Student Management

## When to use this skill

Use this skill when working with student enrollment, updates, promotions, transfers, or any student-related features.

## Architecture Pattern

### Always follow this flow:

Controller → Service → Repository → Model

### Example:

```php
// Controller (thin)
public function store(StoreStudentRequest $request)
{
    $this->authorize('create', Student::class);
    $student = $this->studentService->createStudent(
        $request->validated(),
        Auth::user()->school->id
    );
    return redirect()->route('students.index');
}

// Service (business logic)
public function createStudent(array $data, int $schoolId)
{
    return DB::transaction(function () use ($data, $schoolId) {
        $student = $this->studentRepository->create($data);
        event(new StudentRegistered($student));
        return $student;
    });
}

// Repository (data access)
public function getBySchool(int $schoolId)
{
    return $this->model->where('school_id', $schoolId)->get();
}
```

## Key Components

**Repository**: `app/Repositories/StudentRepository.php`
**Service**: `app/Services/StudentService.php`
**Controller**: `app/Http/Controllers/StudentController.php`
**Form Requests**: `app/Http/Requests/Student/`
**Policy**: `app/Policies/StudentPolicy.php`

## Critical Rules

1. ✅ ALWAYS use StudentService (never direct model access)
2. ✅ ALWAYS check authorization with `$this->authorize()`
3. ✅ ALWAYS validate with Form Requests
4. ✅ ALWAYS ensure school isolation
5. ✅ ALWAYS use DB transactions for multi-step ops
6. ✅ ALWAYS eager load relationships
