<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\Announcement;
use App\Models\ClassArm;
use App\Models\Department;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create School
        $school = School::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'West African Excellence Academy',
            'school_code' => 'WAEA-001',
            'email' => 'info@waea.edu',
            'phone' => '+2348012345678',
            'website' => 'https://waea.edu',
            'address' => '12 Education Lane, Victoria Island',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'logo' => null,
            'motto' => 'Knowledge, Character, and Excellence',
        ]);

        // 2. Create Roles
        $roleNames = [
            'Owner' => 'School Owner with full access rights',
            'Principal' => 'School Principal directing academic affairs',
            'Vice Principal' => 'Vice Principal assisting the principal',
            'Teacher' => 'Class and subject teacher',
            'Guardian' => 'Parent or legal guardian of students',
            'Student' => 'Student enrolled at the school',
            'Accountant' => 'Financial officer of the school',
            'Librarian' => 'Library administrator',
            'Hostel Master' => 'Hostel supervisor',
        ];

        $roles = [];
        foreach ($roleNames as $name => $desc) {
            $roles[$name] = Role::create([
                'school_id' => $school->id,
                'name' => $name,
                'description' => $desc,
            ]);
        }

        // 3. Create Admin User (Owner)
        $adminUser = User::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'role_id' => $roles['Owner']->id,
            'first_name' => 'Adebayo',
            'last_name' => 'Oluwaseun',
            'other_name' => 'Efe',
            'email' => 'admin@sms.com',
            'email_verified_at' => now(),
            'phone' => '+2348022223333',
            'gender' => 'Male',
            'dob' => '1985-05-15',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        // 4. Create Academic Sessions & Terms
        $session = AcademicSession::create([
            'school_id' => $school->id,
            'name' => '2026/2027',
            'start_date' => '2026-09-01',
            'end_date' => '2027-07-20',
            'is_current' => true,
        ]);

        $term = AcademicTerm::create([
            'school_id' => $school->id,
            'session_id' => $session->id,
            'name' => 'First Term',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-15',
            'is_current' => true,
        ]);

        // 5. Create Departments
        $deptNames = ['Science', 'Arts', 'Commercial', 'Administration'];
        $departments = [];
        foreach ($deptNames as $name) {
            $departments[$name] = Department::create([
                'school_id' => $school->id,
                'name' => $name,
                'description' => $name.' Department',
                'status' => 'active',
            ]);
        }

        // 6. Create Classes
        $classNames = ['JSS1', 'JSS2', 'JSS3', 'SS1', 'SS2', 'SS3'];
        $classes = [];
        foreach ($classNames as $name) {
            $classes[$name] = SchoolClass::create([
                'school_id' => $school->id,
                'name' => $name,
                'level' => $name,
                'description' => $name.' Class Level',
                'status' => 'active',
            ]);
        }

        // 7. Create Subjects
        $subjectNames = [
            ['Mathematics', 'MTH', 'Core', true],
            ['English Language', 'ENG', 'Core', true],
            ['Physics', 'PHY', 'Science', false],
            ['Chemistry', 'CHM', 'Science', false],
            ['Biology', 'BIO', 'Science', false],
            ['Financial Accounting', 'ACC', 'Commercial', false],
            ['Literature in English', 'LIT', 'Arts', false],
            ['Civic Education', 'CIV', 'Core', true],
        ];
        foreach ($subjectNames as $sub) {
            Subject::create([
                'school_id' => $school->id,
                'name' => $sub[0],
                'code' => $sub[1],
                'category' => $sub[2],
                'is_core' => $sub[3],
                'status' => 'active',
            ]);
        }

        // 8. Create Teachers
        $teacherData = [
            ['first' => 'John', 'last' => 'Mensah', 'email' => 'john.mensah@waea.edu', 'dept' => 'Science', 'no' => 'TCH-001'],
            ['first' => 'Chidi', 'last' => 'Okafor', 'email' => 'chidi.okafor@waea.edu', 'dept' => 'Arts', 'no' => 'TCH-002'],
            ['first' => 'Fatoumata', 'last' => 'Diallo', 'email' => 'fatoumata.diallo@waea.edu', 'dept' => 'Commercial', 'no' => 'TCH-003'],
        ];

        $teachers = [];
        foreach ($teacherData as $t) {
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'role_id' => $roles['Teacher']->id,
                'first_name' => $t['first'],
                'last_name' => $t['last'],
                'email' => $t['email'],
                'phone' => '+234800000000'.rand(1, 9),
                'gender' => 'Male',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);

            $teachers[] = Staff::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'department_id' => $departments[$t['dept']]->id,
                'staff_no' => $t['no'],
                'staff_type' => 'Teacher',
                'qualification' => 'B.Sc. Ed / B.Ed',
                'employment_date' => '2022-01-10',
                'salary' => 150000.00,
                'status' => 'active',
            ]);
        }

        // 9. Assign Teachers to Class Arms
        $arms = [];
        foreach ($classes as $cName => $cObj) {
            // Create Arm A
            $arms[] = ClassArm::create([
                'school_id' => $school->id,
                'class_id' => $cObj->id,
                'teacher_id' => $teachers[rand(0, count($teachers) - 1)]->id,
                'name' => $cName.' A',
                'capacity' => 40,
                'status' => 'active',
            ]);
            // Create Arm B
            $arms[] = ClassArm::create([
                'school_id' => $school->id,
                'class_id' => $cObj->id,
                'teacher_id' => $teachers[rand(0, count($teachers) - 1)]->id,
                'name' => $cName.' B',
                'capacity' => 40,
                'status' => 'active',
            ]);
            // Note: teacher_id here refers to the staff ID from the staffs table
        }

        // 10. Create Guardians & Students
        $guardianUser = User::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'role_id' => $roles['Guardian']->id,
            'first_name' => 'Kofi',
            'last_name' => 'Mensah',
            'email' => 'parent@sms.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $guardian = Guardian::create([
            'school_id' => $school->id,
            'user_id' => $guardianUser->id,
            'occupation' => 'Businessman',
            'address' => '34 Lekki Road, Lagos',
            'relationship' => 'Father',
            'status' => 'active',
        ]);

        $studentNames = [
            ['first' => 'Amina', 'last' => 'Mensah', 'email' => 'amina.mensah@student.com', 'admission' => 'ADM-2026-001', 'class' => 'SS1', 'arm' => 'SS1 A'],
            ['first' => 'Kwame', 'last' => 'Mensah', 'email' => 'kwame.mensah@student.com', 'admission' => 'ADM-2026-002', 'class' => 'JSS2', 'arm' => 'JSS2 A'],
            ['first' => 'Chidinma', 'last' => 'Okafor', 'email' => 'chidinma.okafor@student.com', 'admission' => 'ADM-2026-003', 'class' => 'SS3', 'arm' => 'SS3 A'],
            ['first' => 'Mustapha', 'last' => 'Bello', 'email' => 'mustapha.bello@student.com', 'admission' => 'ADM-2026-004', 'class' => 'SS2', 'arm' => 'SS2 B'],
            ['first' => 'Fatou', 'last' => 'Sow', 'email' => 'fatou.sow@student.com', 'admission' => 'ADM-2026-005', 'class' => 'JSS1', 'arm' => 'JSS1 B'],
        ];

        $studentList = [];
        foreach ($studentNames as $s) {
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'role_id' => $roles['Student']->id,
                'first_name' => $s['first'],
                'last_name' => $s['last'],
                'email' => $s['email'],
                'phone' => null,
                'gender' => rand(0, 1) ? 'Male' : 'Female',
                'dob' => '2012-04-10',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);

            // Find matching Class and Arm
            $cls = $classes[$s['class']];
            $arm = ClassArm::where('school_id', $school->id)->where('name', $s['arm'])->first();

            $student = Student::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'user_id' => $user->id,
                'admission_no' => $s['admission'],
                'class_id' => $cls->id,
                'arm_id' => $arm ? $arm->id : null,
                'admission_date' => '2026-01-10',
                'photo' => null,
                'status' => 'active',
            ]);

            $studentList[] = $student;

            // Link parent Mensahs to parent Kofi
            if (str_contains($s['email'], 'mensah')) {
                $guardian->students()->attach($student->id, ['school_id' => $school->id, 'relationship' => 'Father', 'is_primary' => true]);
            }
        }

        // 11. Create Student Attendance Data for the chart
        $dates = [
            '2026-07-07', '2026-07-08', '2026-07-09', '2026-07-10', '2026-07-11', '2026-07-12', '2026-07-13',
        ];
        foreach ($dates as $d) {
            foreach ($studentList as $s) {
                // Determine a status (mostly present, some late/absent)
                $rand = rand(1, 100);
                $status = 'present';
                if ($rand > 92) {
                    $status = 'absent';
                } elseif ($rand > 85) {
                    $status = 'late';
                }

                StudentAttendance::create([
                    'school_id' => $school->id,
                    'student_id' => $s->id,
                    'class_id' => $s->class_id,
                    'session_id' => $session->id,
                    'term_id' => $term->id,
                    'attendance_date' => $d,
                    'status' => $status,
                    'remark' => 'Automated seeder entry',
                    'recorded_by' => $teachers[0]->id,
                ]);
            }
        }

        // 12. Create Announcements
        Announcement::create([
            'school_id' => $school->id,
            'title' => 'Inter-House Sports Calendar Released',
            'body' => 'Get ready for the annual inter-house sports starting early next month!',
            'target' => 'Everyone',
            'published_by' => $adminUser->id,
            'announced_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        Announcement::create([
            'school_id' => $school->id,
            'title' => 'WAEC Registration Extended',
            'body' => 'The deadline for Senior Secondary School WAEC registration has been extended to August 10.',
            'target' => 'Everyone',
            'published_by' => $adminUser->id,
            'announced_at' => now()->subDays(2),
            'expires_at' => now()->addDays(15),
            'status' => 'active',
        ]);

        // 13. Create settings
        $settingsData = [
            'school_theme' => 'emerald',
            'currency' => 'NGN',
            'timezone' => 'Africa/Lagos',
            'sms_provider' => 'Termii',
            'email_provider' => 'Mailgun',
        ];
        foreach ($settingsData as $k => $v) {
            Setting::create([
                'school_id' => $school->id,
                'key' => $k,
                'value' => $v,
            ]);
        }
    }
}
