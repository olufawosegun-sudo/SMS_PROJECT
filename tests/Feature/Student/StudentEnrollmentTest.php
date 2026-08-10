<?php

namespace Tests\Feature\Student;

use App\Events\StudentRegistered;
use App\Mail\StudentWelcomeMail;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected $school;

    protected $principalUser;

    protected $principalRole;

    protected $studentRole;

    protected $schoolClass;

    protected function setUp(): void
    {
        parent::setUp();

        // Create school
        $this->school = School::factory()->create([
            'name' => 'Test School',
        ]);

        // Create roles
        $this->principalRole = Role::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Principal',
        ]);

        $this->studentRole = Role::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Student',
        ]);

        // Create principal user
        $this->principalUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $this->principalRole->id,
        ]);

        // Create class
        $this->schoolClass = SchoolClass::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Grade 1',
        ]);
    }

    /** @test */
    public function principal_can_view_student_enrollment_page()
    {
        $response = $this->actingAs($this->principalUser)
            ->get(route('students.create'));

        $response->assertStatus(200);
        $response->assertViewIs('students.create');
    }

    /** @test */
    public function principal_can_enroll_new_student()
    {
        Mail::fake();
        Event::fake();

        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'gender' => 'male',
            'date_of_birth' => '2010-01-15',
            'class_id' => $this->schoolClass->id,
            'admission_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->principalUser)
            ->post(route('students.store'), $studentData);

        // Assert redirect with success message
        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');

        // Assert student was created in database
        $this->assertDatabaseHas('students', [
            'school_id' => $this->school->id,
            'class_id' => $this->schoolClass->id,
            'status' => 'active',
        ]);

        // Assert user was created
        $this->assertDatabaseHas('users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'school_id' => $this->school->id,
            'role_id' => $this->studentRole->id,
        ]);

        // Assert event was fired
        Event::assertDispatched(StudentRegistered::class);

        // Assert email was queued (via listener)
        // Note: This tests the listener was called
        Mail::assertQueued(StudentWelcomeMail::class);
    }

    /** @test */
    public function student_enrollment_requires_required_fields()
    {
        $response = $this->actingAs($this->principalUser)
            ->post(route('students.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'gender',
            'date_of_birth',
            'class_id',
        ]);
    }

    /** @test */
    public function student_email_must_be_unique()
    {
        // Create existing user with email
        User::factory()->create([
            'email' => 'existing@example.com',
            'school_id' => $this->school->id,
        ]);

        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'existing@example.com', // Duplicate
            'gender' => 'male',
            'date_of_birth' => '2010-01-15',
            'class_id' => $this->schoolClass->id,
        ];

        $response = $this->actingAs($this->principalUser)
            ->post(route('students.store'), $studentData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function admission_number_is_auto_generated_if_not_provided()
    {
        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '2010-01-15',
            'class_id' => $this->schoolClass->id,
        ];

        $this->actingAs($this->principalUser)
            ->post(route('students.store'), $studentData);

        $student = Student::first();

        $this->assertNotNull($student->admission_no);
        $this->assertMatchesRegularExpression('/^STU\d+/', $student->admission_no);
    }

    /** @test */
    public function custom_admission_number_can_be_provided()
    {
        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '2010-01-15',
            'class_id' => $this->schoolClass->id,
            'admission_no' => 'CUSTOM001',
        ];

        $this->actingAs($this->principalUser)
            ->post(route('students.store'), $studentData);

        $this->assertDatabaseHas('students', [
            'admission_no' => 'CUSTOM001',
        ]);
    }

    /** @test */
    public function unauthorized_user_cannot_enroll_students()
    {
        // Create a regular teacher (not principal)
        $teacherRole = Role::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Teacher',
        ]);

        $teacher = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $teacherRole->id,
        ]);

        $studentData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '2010-01-15',
            'class_id' => $this->schoolClass->id,
        ];

        $response = $this->actingAs($teacher)
            ->post(route('students.store'), $studentData);

        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function student_can_be_updated()
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->schoolClass->id,
        ]);

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Updated',
            'gender' => $student->user->gender,
            'date_of_birth' => $student->user->date_of_birth,
            'class_id' => $this->schoolClass->id,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->principalUser)
            ->put(route('students.update', $student), $updateData);

        $response->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('users', [
            'id' => $student->user_id,
            'first_name' => 'Jane',
            'last_name' => 'Updated',
        ]);
    }

    /** @test */
    public function student_can_be_soft_deleted()
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->schoolClass->id,
        ]);

        $response = $this->actingAs($this->principalUser)
            ->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));

        // Assert soft deleted
        $this->assertSoftDeleted('students', [
            'id' => $student->id,
        ]);

        $this->assertSoftDeleted('users', [
            'id' => $student->user_id,
        ]);
    }
}
