# Contributing to School Management System

First off, thank you for considering contributing to SMS! It's people like you that make SMS such a great tool.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Pull Request Process](#pull-request-process)
- [Coding Standards](#coding-standards)
- [Testing Guidelines](#testing-guidelines)
- [Commit Messages](#commit-messages)

---

## 📜 Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

### Our Standards

- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

---

## 🤝 How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce**
- **Expected vs actual behavior**
- **Screenshots** (if applicable)
- **Environment details** (OS, PHP version, Laravel version)

**Template:**
```markdown
## Bug Description
A clear description of what the bug is.

## Steps to Reproduce
1. Go to '...'
2. Click on '...'
3. See error

## Expected Behavior
What you expected to happen.

## Actual Behavior
What actually happened.

## Environment
- OS: Windows 11
- PHP: 8.2
- Laravel: 11.x
- Browser: Chrome 120
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. Include:

- **Clear title and description**
- **Use case** - Why is this enhancement useful?
- **Proposed solution**
- **Alternative solutions** considered
- **Additional context** or screenshots

### Your First Code Contribution

Unsure where to begin? Look for issues tagged with:
- `good first issue` - Good for newcomers
- `help wanted` - Extra attention needed

---

## 🛠️ Development Setup

### 1. Fork and Clone
```bash
# Fork the repository on GitHub
# Clone your fork
git clone https://github.com/YOUR_USERNAME/sms-project.git
cd sms-project
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### 4. Create a Branch
```bash
git checkout -b feature/your-feature-name
```

---

## 🔄 Pull Request Process

### Before Submitting

1. **Update documentation** - Update README.md if needed
2. **Add tests** - New features require tests
3. **Run tests** - Ensure all tests pass
4. **Check code style** - Run `./vendor/bin/pint`
5. **Update CHANGELOG** - Add your changes

### Pull Request Checklist

- [ ] Code follows PSR-12 standards
- [ ] Tests added for new features
- [ ] All tests passing (`php artisan test`)
- [ ] Documentation updated
- [ ] Commit messages follow conventions
- [ ] No merge conflicts
- [ ] Branch is up to date with main

### PR Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## How Has This Been Tested?
- [ ] Unit tests
- [ ] Feature tests
- [ ] Manual testing

## Screenshots (if applicable)

## Checklist
- [ ] My code follows the style guidelines
- [ ] I have performed a self-review
- [ ] I have commented my code where needed
- [ ] I have updated the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests
- [ ] All tests pass locally
```

---

## 📝 Coding Standards

### PSR-12 Compliance

This project follows [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.

```bash
# Format code automatically
./vendor/bin/pint

# Check for issues
./vendor/bin/phpstan analyse
```

### Naming Conventions

**Controllers:**
```php
✅ StudentController
❌ studentsController, Students
```

**Models:**
```php
✅ Student (singular)
❌ Students (plural)
```

**Methods:**
```php
✅ public function getActiveStudents()  // camelCase
❌ public function GetActiveStudents()  // PascalCase
```

**Variables:**
```php
✅ $studentName
❌ $student_name, $StudentName
```

### Architecture Patterns

Follow existing patterns:

**Repository Pattern:**
```php
// Good
class StudentRepository extends BaseRepository
{
    public function getBySchool(int $schoolId) {}
}
```

**Service Pattern:**
```php
// Good
class StudentService
{
    protected $studentRepository;
    
    public function createStudent(array $data, int $schoolId) {}
}
```

**Controller Pattern:**
```php
// Good - Thin controller
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

### Documentation

Add PHPDoc blocks:

```php
/**
 * Create a new student with user account.
 *
 * @param array $data Student data
 * @param int $schoolId School ID
 * @return Student Created student instance
 * @throws \Exception If creation fails
 */
public function createStudent(array $data, int $schoolId): Student
{
    // Implementation
}
```

---

## 🧪 Testing Guidelines

### Write Tests for:
- ✅ New features
- ✅ Bug fixes
- ✅ Business logic
- ✅ Edge cases

### Don't Test:
- ❌ Framework features
- ❌ Third-party packages
- ❌ Simple getters/setters

### Test Structure

**Unit Tests:**
```php
/** @test */
public function it_can_create_student_with_valid_data()
{
    // Arrange
    $mockRepo = Mockery::mock(StudentRepository::class);
    $service = new StudentService($mockRepo);
    
    // Act
    $result = $service->someMethod();
    
    // Assert
    $this->assertEquals($expected, $result);
}
```

**Feature Tests:**
```php
/** @test */
public function principal_can_enroll_new_student()
{
    // Arrange
    $principal = User::factory()->create(['role' => 'Principal']);
    
    // Act
    $response = $this->actingAs($principal)
        ->post(route('students.store'), $validData);
    
    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('students', ['first_name' => 'John']);
}
```

### Running Tests

```bash
# All tests
php artisan test

# Specific file
php artisan test tests/Unit/Services/StudentServiceTest.php

# With coverage
php artisan test --coverage

# Stop on failure
php artisan test --stop-on-failure
```

---

## 📌 Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

### Format
```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

### Examples

```bash
# Feature
feat(student): add bulk enrollment feature

Implement bulk CSV import for student enrollment with validation
and error reporting.

Closes #123

# Bug fix
fix(payment): resolve duplicate payment issue

Fixed race condition in payment processing that caused duplicate
entries when users clicked submit multiple times.

Fixes #456

# Documentation
docs(readme): update installation instructions

Added missing steps for queue configuration and improved
formatting of code examples.
```

---

## 🎯 Best Practices

### Code Quality

1. **DRY** - Don't Repeat Yourself
2. **KISS** - Keep It Simple, Stupid
3. **YAGNI** - You Aren't Gonna Need It
4. **SOLID** - Follow SOLID principles

### Performance

- Use eager loading to prevent N+1 queries
- Cache expensive operations
- Use queue jobs for long-running tasks
- Optimize database indexes

### Security

- Validate all inputs
- Use policies for authorization
- Sanitize outputs
- Never commit sensitive data
- Use environment variables for secrets

---

## 📞 Getting Help

- **Documentation**: Check `/docs` folder
- **Discussions**: GitHub Discussions
- **Issues**: GitHub Issues
- **Email**: dev@example.com

---

## 🎉 Recognition

Contributors will be recognized in:
- GitHub contributors page
- README.md acknowledgments
- Release notes

---

Thank you for contributing! 🙏
