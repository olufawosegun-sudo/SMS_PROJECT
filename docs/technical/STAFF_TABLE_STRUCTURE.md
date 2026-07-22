# 🏢 **STAFF TABLE STRUCTURE - UNIFIED EMPLOYEE MANAGEMENT**

## **📊 NEW DATABASE DESIGN**

### **Before (Old Design):**
```
users
  ├── teachers table (teacher-specific data)
  └── principals table (principal-specific data)
```

### **After (New Design):**
```
users
  └── staff table (ALL employees: principals, teachers, accountants, librarians, etc.)
        └── teacher_subjects table (ONLY for teaching staff)
```

---

## **✅ BENEFITS OF STAFF TABLE**

### **1. Unified Employee Management**
All employees share common fields:
- Staff number
- Salary
- Employment date
- Contract type
- Office location
- Emergency contacts
- Bank details

### **2. Consistency**
- One table for all employees
- Same structure for HR data
- Easier to maintain

### **3. Scalability**
Easy to add new staff types:
- Accountant
- Librarian
- Nurse
- Security Guard
- Driver
- IT Admin

### **4. Better Reporting**
- Single query for all staff
- Easy payroll processing
- Simple attendance tracking

---

## **🎯 STAFF TYPES**

All stored in `staff.staff_type` field:

| Staff Type | Description |
|------------|-------------|
| **Principal** | School head |
| **Vice Principal** | Deputy head |
| **Assistant Principal** | Assistant admin |
| **Teacher** | Teaching staff |
| **Accountant** | Finance officer |
| **Librarian** | Library manager |
| **Hostel Master** | Hostel supervisor |
| **Nurse** | Medical officer |
| **Driver** | School driver |
| **Security** | Security guard |
| **IT Admin** | IT support |
| **Receptionist** | Front desk |

---

## **📋 STAFF TABLE FIELDS**

### **Identification**
- `staff_no` - Unique staff number (e.g., STF00001, TCH00001)
- `staff_type` - Type of staff (Principal, Teacher, etc.)

### **Employment**
- `employment_date` - Start date
- `confirmation_date` - End of probation
- `employment_type` - full-time, part-time, contract
- `contract_type` - permanent, contract, probation
- `contract_start_date` - Contract start
- `contract_end_date` - Contract end

### **Education**
- `qualification` - Degree/certification
- `specialization` - Area of expertise
- `years_of_experience` - Total years

### **Financial**
- `salary` - Monthly/weekly salary
- `payment_frequency` - monthly, bi-weekly, weekly
- `bank_name` - Bank for salary
- `account_number` - Account number
- `account_name` - Account holder name

### **Work Details**
- `office_location` - Office/desk location
- `job_description` - Role description

### **Emergency**
- `emergency_contact_name` - Emergency person
- `emergency_contact_phone` - Emergency phone
- `emergency_contact_relationship` - Relationship

### **Documents**
- `appointment_letter` - File path
- `resume_cv` - File path
- `certificates` - Array of file paths

### **Status**
- `status` - active, inactive, on_leave, suspended, resigned, retired, terminated
- `resignation_date` - When they resigned
- `termination_date` - When terminated
- `exit_notes` - Exit interview notes

---

## **🔗 RELATIONSHIPS**

### **Staff → User**
```php
$staff->user // Get authentication account
$staff->user->email // Get email
$staff->user->first_name // Get first name
```

### **Staff → Department**
```php
$staff->department // Get department (if assigned)
```

### **Staff → Teaching (if they teach)**
```php
$staff->teacherSubjects // Get subjects taught
```

### **User → Staff**
```php
$user->staff // Get staff profile
$user->staff->staff_no // Get staff number
$user->staff->salary // Get salary
```

---

## **📝 EXAMPLE USAGE**

### **Create a Principal:**
```php
// 1. Create User
$user = User::create([...]);

// 2. Create Staff profile
$staff = Staff::create([
    'school_id' => $school->id,
    'user_id' => $user->id,
    'staff_no' => 'PRI00001',
    'staff_type' => 'Principal',
    'qualification' => 'PhD in Education',
    'salary' => 500000,
    'employment_date' => now(),
    'status' => 'active',
]);
```

### **Create a Teacher Who Teaches:**
```php
// 1. Create User
$user = User::create([...]);

// 2. Create Staff profile
$staff = Staff::create([
    'school_id' => $school->id,
    'user_id' => $user->id,
    'staff_no' => 'TCH00001',
    'staff_type' => 'Teacher',
    'qualification' => 'B.Ed Mathematics',
    'salary' => 150000,
    'employment_date' => now(),
    'status' => 'active',
]);

// 3. Assign subjects (ONLY for teaching staff)
TeacherSubject::create([
    'school_id' => $school->id,
    'staff_id' => $staff->id, // ← Now uses staff_id
    'class_id' => $classId,
    'subject_id' => $subjectId,
]);
```

### **Create an Accountant (Non-teaching):**
```php
// 1. Create User
$user = User::create([...]);

// 2. Create Staff profile (NO teacher_subjects needed)
$staff = Staff::create([
    'school_id' => $school->id,
    'user_id' => $user->id,
    'staff_no' => 'ACC00001',
    'staff_type' => 'Accountant',
    'qualification' => 'B.Sc Accounting, ACA',
    'salary' => 200000,
    'employment_date' => now(),
    'status' => 'active',
]);

// No teacher_subjects entry - they don't teach!
```

---

## **🔍 QUERIES**

### **Get all staff:**
```php
$allStaff = Staff::where('school_id', $schoolId)
    ->where('status', 'active')
    ->get();
```

### **Get all principals:**
```php
$principals = Staff::where('school_id', $schoolId)
    ->where('staff_type', 'Principal')
    ->get();
```

### **Get all teachers:**
```php
$teachers = Staff::where('school_id', $schoolId)
    ->where('staff_type', 'Teacher')
    ->get();
```

### **Get teaching staff (who teach subjects):**
```php
$teachingStaff = Staff::where('school_id', $schoolId)
    ->whereHas('teacherSubjects')
    ->get();
```

### **Get total payroll:**
```php
$totalSalary = Staff::where('school_id', $schoolId)
    ->where('status', 'active')
    ->sum('salary');
```

---

## **✅ MIGRATION STATUS**

- ✅ `staff` table created
- ✅ `Staff` model created
- ⏳ `teacher_subjects` needs migration (change teacher_id to staff_id)
- ⏳ Update controllers to use Staff instead of Teacher/Principal
- ⏳ Migrate existing data from teachers/principals to staff
- ⏳ Drop old teachers and principals tables

---

## **📚 NEXT STEPS**

1. Run migration to update teacher_subjects table
2. Create data migration script (move teachers → staff)
3. Update TeacherController to use Staff
4. Update PrincipalController to use Staff
5. Update views to reference staff instead of teachers
6. Test all functionality
7. Drop old teachers and principals tables

---

**This is a much better, scalable design for a multi-school SaaS!** 🎉
