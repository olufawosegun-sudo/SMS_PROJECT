# 📊 School Management System - Complete Database Documentation

## 🎯 Database Overview

**Total Tables:** 98  
**Architecture:** Multi-Tenant (School-based isolation)  
**DBMS:** MySQL  
**Purpose:** Complete School Management for West African Schools  
**Version:** 1.0  
**Last Updated:** 2026-07-13

---

## 📑 Table of Contents

1. [Core Multi-Tenant Layer](#1-core-multi-tenant-layer)
2. [User & Authentication](#2-user--authentication)
3. [Academic Structure](#3-academic-structure)
4. [Staff Management](#4-staff-management)
5. [Student & Guardian Management](#5-student--guardian-management)
6. [Attendance System](#6-attendance-system)
7. [Academic Content & Timetable](#7-academic-content--timetable)
8. [Assessment & Examinations](#8-assessment--examinations)
9. [Results & Grading](#9-results--grading)
10. [Fees & Financial Management](#10-fees--financial-management)
11. [Library Management](#11-library-management)
12. [Hostel Management](#12-hostel-management)
13. [Transport Management](#13-transport-management)
14. [Extracurricular Activities](#14-extracurricular-activities)
15. [Communication System](#15-communication-system)
16. [Admission Management](#16-admission-management)
17. [Facilities & Infrastructure](#17-facilities--infrastructure)
18. [System Administration](#18-system-administration)
19. [Subscription & Billing](#19-subscription--billing)
20. [Calendar & Meetings](#20-calendar--meetings)
21. [Relationship Summary](#relationship-summary)
22. [Data Flow Examples](#data-flow-examples)

---

## 🏢 **1. CORE MULTI-TENANT LAYER**

### **SCHOOLS** (Root Entity - Central Hub)

```
┌─────────────────────────────────────────────────────────────────┐
│                         🏢 SCHOOLS (Root)                       │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • uuid (Unique) - Public identifier                            │
│ • school_code (Unique) - e.g., "23SSE23"                      │
│ • name - School name                                           │
│ • email, phone, address                                        │
│ • country, state, city                                         │
│ • logo, motto, website                                         │
│ • currency (NGN, GHS, SLL) - West African currencies          │
│ • status (active/inactive/suspended)                           │
│ • created_at, updated_at                                       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ school_id (FK) - Every table branches here
                              │ This ensures complete data isolation
                              │
         ┌────────────────────┼────────────────────┬──────────────┐
         │                    │                    │              │
         ▼                    ▼                    ▼              ▼
      USERS              ACADEMIC_          DEPARTMENTS      SETTINGS
      ROLES              SESSIONS           CLASSES          And 90+ more
      PERMISSIONS        FEE_CATEGORIES     SUBJECTS         tables...
```

**Purpose:** Root entity that owns all other data. Every school is completely isolated.

**Relationships:**
- 1 School → Many Users, Roles, Permissions, Students, Teachers, etc.
- **Total child tables:** 97 tables reference SCHOOLS via `school_id`

**Business Rules:**
- Each school operates independently
- Data cannot cross school boundaries
- Users can only see their school's data
- Supports unlimited schools (multi-tenant SaaS)

---

## 👥 **2. USER & AUTHENTICATION**

### **System Overview**
The authentication system provides role-based access control with multi-tenant isolation.

### **Complete Authentication Flow Diagram**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    USER IDENTITY & ACCESS CONTROL CHAIN                     │
└────────────────────────────────────────────────────────────────────────────┘

    SCHOOLS (1) ──── school_id ────┐
                                   │
                    ┌──────────────┴──────────────┐
                    │                             │
                    ▼                             ▼
              ROLES (Many)                 PERMISSIONS (Many)
        ┌─────────────────┐              ┌──────────────────┐
        │ id, school_id   │              │ id, school_id    │
        │ name            │              │ name, module     │
        │ description     │              │ description      │
        │ is_system_role  │              └──────────────────┘
        └─────────────────┘                       │
                │                                  │
                │ role_id                  permission_id
                │                                  │
                └──────────┬──────────────────────┘
                           │
                           ▼
                  ROLE_PERMISSIONS (Junction)
                ┌────────────────────────┐
                │ id, school_id          │
                │ role_id (FK)           │
                │ permission_id (FK)     │
                └────────────────────────┘
                           │
            ┌──────────────┴──────────────┐
            │                             │
            ▼                             │
        USERS (Many)                      │
    ┌──────────────────────┐             │
    │ id, school_id        │◄────────────┘
    │ role_id (FK)         │
    │ uuid (Unique)        │
    │ first_name           │
    │ last_name            │
    │ email (Unique)       │
    │ phone, password      │
    │ gender, DOB          │
    │ profile_photo        │
    │ status               │
    │ email_verified_at    │
    │ deleted_at (Soft)    │
    └──────────────────────┘
            │
            ├──→ TEACHERS (1:1 via user_id)
            │       ├─ TEACHER_SUBJECTS
            │       ├─ TEACHER_ATTENDANCE
            │       ├─ STAFF_LEAVE_REQUESTS
            │       └─ TEACHER_PAYROLL
            │
            ├──→ GUARDIANS (1:1 via user_id)
            │       └─ GUARDIAN_STUDENTS
            │
            ├──→ LOGIN_SESSIONS (Many)
            ├──→ NOTIFICATIONS (Many)
            ├──→ ACTIVITY_LOGS (Many)
            ├──→ AUDIT_LOGS (Many)
            ├──→ MESSAGES (as sender/receiver)
            └──→ API_TOKENS (Many)
```

### **USERS Table**
```
┌────────────────────────────┐
│ USERS                      │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • role_id (FK)            │  → ROLES
│ • uuid (Unique)           │  Public identifier
│ • first_name              │
│ • last_name               │
│ • email (Unique per       │  Unique constraint: (school_id, email)
│   school)                 │
│ • phone                   │
│ • password (hashed)       │
│ • gender                  │
│ • date_of_birth           │
│ • profile_photo           │
│ • status (active/inactive/│
│   suspended)              │
│ • email_verified_at       │
│ • deleted_at (Soft Delete)│
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Relationships:**
- Many Users → 1 School
- Many Users → 1 Role
- 1 User → 0..1 Teacher (optional)
- 1 User → 0..1 Guardian (optional)
- 1 User → Many Login Sessions, Notifications, Activity Logs

**Business Rules:**
- Email must be unique within a school (not globally)
- One user can only have one role
- A user can be either a Teacher OR Guardian (not both)
- System roles (Owner, Admin) have elevated permissions

### **ROLES Table**
```
┌──────────────────────┐
│ ROLES                │
├──────────────────────┤
│ • id (PK)           │
│ • school_id (FK)    │  → SCHOOLS
│ • name              │  e.g., Owner, Admin, Teacher, Guardian
│ • description       │
│ • is_system_role    │  Cannot be deleted if true
│ • created_at        │
│ • updated_at        │
└──────────────────────┘
```

**Default System Roles:**
- **Owner:** Full system access, can manage everything
- **Admin:** School management, cannot delete school
- **Teacher:** Teaching, grading, attendance
- **Guardian:** View children's data, pay fees
- **Student:** (Future) View own data

### **PERMISSIONS Table**
```
┌──────────────────────────┐
│ PERMISSIONS              │
├──────────────────────────┤
│ • id (PK)               │
│ • school_id (FK)        │  → SCHOOLS
│ • name                  │  e.g., view-students, create-invoices
│ • module                │  e.g., students, finance, academics
│ • description           │
│ • created_at            │
│ • updated_at            │
└──────────────────────────┘
```

**Permission Modules:**
- dashboard, users, students, teachers, guardians
- academics, attendance, timetable, subjects
- assessments, examinations, results
- finance, fees, invoices, payments
- library, hostel, transport
- communication, reports, settings

### **ROLE_PERMISSIONS Table** (Junction)
```
┌────────────────────────┐
│ ROLE_PERMISSIONS       │
├────────────────────────┤
│ • id (PK)             │
│ • school_id (FK)      │  → SCHOOLS
│ • role_id (FK)        │  → ROLES
│ • permission_id (FK)  │  → PERMISSIONS
│ • created_at          │
│ • updated_at          │
└────────────────────────┘
```

**Purpose:** Maps which permissions each role has

### **LOGIN_SESSIONS Table**
```
┌────────────────────────────┐
│ LOGIN_SESSIONS             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • user_id (FK)            │  → USERS
│ • ip_address              │
│ • user_agent              │  Browser/device info
│ • login_at                │
│ • logout_at (nullable)    │
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Purpose:** Track user login history for security auditing

---

## 🎓 **3. ACADEMIC STRUCTURE**

### **Complete Academic Hierarchy**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         ACADEMIC STRUCTURE TREE                             │
└────────────────────────────────────────────────────────────────────────────┘

SCHOOLS (1)
    │
    ├──→ ACADEMIC_SESSIONS (Many) ─────────────┐
    │       │ • id, school_id                   │
    │       │ • name (e.g., "2024/2025")       │
    │       │ • start_date, end_date           │
    │       │ • is_current (boolean)           │
    │       │                                   │
    │       └──→ ACADEMIC_TERMS (Many)         │
    │               │ • id, school_id,          │
    │               │   session_id (FK)         │
    │               │ • name ("1st Term")       │
    │               │ • term_number (1,2,3)     │
    │               │ • start_date, end_date    │
    │               │ • is_current              │
    │               │                            │
    │               └──→ Referenced by:          │
    │                     - INVOICES            │
    │                     - RESULTS             │
    │                     - FEE_STRUCTURES      │
    │                     - EXAMINATIONS        │
    │                     - CONTINUOUS_ASSESSMENTS
    │                     - LESSON_PLANS        │
    │                     - TEACHER_SUBJECTS    │
    │
    ├──→ DEPARTMENTS (Many)
    │       │ • id, school_id
    │       │ • head_teacher_id (FK → TEACHERS)
    │       │ • name (Sciences, Arts, Commercial)
    │       │ • code, description, status
    │       │
    │       ├──→ CLASSES (Many)
    │       │       │ • id, school_id,
    │       │       │   department_id (FK)
    │       │       │ • name (JSS1, JSS2, SS1, SS2)
    │       │       │ • code, level, capacity
    │       │       │
    │       │       ├──→ CLASS_ARMS (Many)
    │       │       │       │ • id, school_id,
    │       │       │       │   class_id (FK)
    │       │       │       │ • teacher_id (FK → TEACHERS)
    │       │       │       │   [Form Teacher/Class Teacher]
    │       │       │       │ • name (A, B, C, D)
    │       │       │       │ • capacity
    │       │       │       │
    │       │       │       └──→ STUDENTS (Many via arm_id)
    │       │       │
    │       │       └──→ STUDENTS (Many via class_id)
    │       │       └──→ TIMETABLES (Many)
    │       │       └──→ TEACHER_SUBJECTS (Many)
    │       │       └──→ ASSIGNMENTS (Many)
    │       │       └──→ CONTINUOUS_ASSESSMENTS (Many)
    │       │       └──→ CBT_EXAMS (Many)
    │       │       └──→ FEE_STRUCTURES (Many)
    │       │
    │       ├──→ SUBJECTS (Many)
    │       │       │ • id, school_id,
    │       │       │   department_id (FK)
    │       │       │ • name (Mathematics, English)
    │       │       │ • code, description
    │       │       │ • is_core (compulsory?)
    │       │       │ • credit_unit
    │       │       │
    │       │       └──→ Referenced by:
    │       │             - TEACHER_SUBJECTS
    │       │             - ASSIGNMENTS
    │       │             - EXAM_SCORES
    │       │             - CONTINUOUS_ASSESSMENTS
    │       │             - TIMETABLES
    │       │
    │       └──→ TEACHERS (Many)
    │               • id, school_id,
    │                 department_id (FK)
    │
    └──→ TEACHERS (Many - can be without department)
            │ • id, school_id
            │ • user_id (FK → USERS) [1:1]
            │ • department_id (FK, nullable)
            │ • staff_no (Unique per school)
            │ • qualification, employment_date
            │ • salary, status
            │ • deleted_at (Soft Delete)
```

### **ACADEMIC_SESSIONS Table**
```
┌────────────────────────────┐
│ ACADEMIC_SESSIONS          │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • name                    │  e.g., "2024/2025", "2025/2026"
│ • start_date              │  Session start
│ • end_date                │  Session end
│ • is_current (boolean)    │  Only one active at a time
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Business Rules:**
- Only ONE session can be `is_current = true` per school
- Sessions typically span one academic year (Sep-Jul in West Africa)
- Cannot delete session with existing data

### **ACADEMIC_TERMS Table**
```
┌────────────────────────────┐
│ ACADEMIC_TERMS             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • session_id (FK)         │  → ACADEMIC_SESSIONS
│ • name                    │  "1st Term", "2nd Term", "3rd Term"
│ • term_number             │  1, 2, 3
│ • start_date              │
│ • end_date                │
│ • is_current (boolean)    │  Only one active at a time
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Business Rules:**
- 3 terms per academic session (West African standard)
- Only ONE term can be `is_current = true` per school
- Terms cannot overlap within same session

### **DEPARTMENTS Table**
```
┌────────────────────────────┐
│ DEPARTMENTS                │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • head_teacher_id (FK)    │  → TEACHERS (nullable)
│ • name                    │  Sciences, Arts, Commercial
│ • code                    │  SCI, ART, COM
│ • description             │
│ • status                  │  active/inactive
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Common West African Departments:**
- **Sciences:** Biology, Chemistry, Physics
- **Arts:** Literature, Government, History
- **Commercial:** Accounting, Commerce, Economics

### **CLASSES Table**
```
┌────────────────────────────┐
│ CLASSES                    │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • department_id (FK)      │  → DEPARTMENTS (nullable for junior classes)
│ • name                    │  JSS1, JSS2, JSS3, SS1, SS2, SS3
│ • code                    │  J1, J2, J3, S1, S2, S3
│ • level                   │  Junior/Senior
│ • capacity                │  Max students per class
│ • created_at, updated_at  │
└────────────────────────────┘
```

**West African Class Levels:**
- **Junior Secondary:** JSS1, JSS2, JSS3 (Ages 12-14)
- **Senior Secondary:** SS1, SS2, SS3 (Ages 15-17)

### **CLASS_ARMS Table**
```
┌────────────────────────────┐
│ CLASS_ARMS                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • class_id (FK)           │  → CLASSES
│ • teacher_id (FK)         │  → TEACHERS (Form Teacher, nullable)
│ • name                    │  A, B, C, D, E
│ • capacity                │  Max students per arm
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Purpose:** Divides classes into smaller groups (arms/sections)
- Example: SS2 might have SS2A, SS2B, SS2C
- Each arm has a Form Teacher (class teacher)

### **SUBJECTS Table**
```
┌────────────────────────────┐
│ SUBJECTS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • department_id (FK)      │  → DEPARTMENTS (nullable)
│ • name                    │  Mathematics, English, Biology
│ • code                    │  MTH, ENG, BIO
│ • description             │
│ • is_core (boolean)       │  Compulsory for all?
│ • credit_unit             │  For grading weight
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Core vs Elective:**
- **Core:** English, Mathematics, Civic Education (compulsory for all)
- **Elective:** Department-specific subjects

**Relationships:**
- 1 Department → Many Subjects
- Many Teachers ↔ Many Subjects (via TEACHER_SUBJECTS)

---

## 👨‍🏫 **4. STAFF MANAGEMENT** (Unified Employee System)

### **Complete Staff Management Structure**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    UNIFIED STAFF SYSTEM (All Employees)                     │
└────────────────────────────────────────────────────────────────────────────┘

SCHOOLS (1)
    │
    └──→ DEPARTMENTS (Many)
            │ • id, school_id
            │ • head_staff_id (FK → STAFF, nullable)
            │ • name, code, description
            │
            └──→ STAFF (Many)
                    │ • id, school_id
                    │ • user_id (FK → USERS) [1:1]
                    │ • department_id (FK, nullable)
                    │ • staff_no (Unique per school)
                    │ • staff_type (enum)
                    │ • qualification, specialization
                    │ • employment_date
                    │ • years_of_experience
                    │ • previous_school
                    │ • office_location
                    │ • emergency_contact
                    │ • emergency_contact_relationship
                    │ • contract_type (permanent/contract/temporary)
                    │ • contract_start_date, contract_end_date
                    │ • salary
                    │ • bank_name, account_number, account_name
                    │ • tax_id, pension_id
                    │ • marital_status
                    │ • number_of_children
                    │ • residential_address
                    │ • state_of_origin, lga, nationality
                    │ • religion
                    │ • blood_group
                    │ • disabilities
                    │ • next_of_kin_name, next_of_kin_phone
                    │ • next_of_kin_relationship
                    │ • highest_degree, institution
                    │ • graduation_year
                    │ • professional_certifications
                    │ • languages_spoken
                    │ • status (active/inactive/suspended/resigned)
                    │ • deleted_at (Soft Delete)
                    │
                    ├──→ TEACHER_SUBJECTS (Many) [Only for teachers]
                    │       Teaching assignments
                    │
                    ├──→ STAFF_ATTENDANCE (Many)
                    │       Daily attendance tracking
                    │
                    ├──→ STAFF_LEAVE_REQUESTS (Many)
                    │       Leave applications
                    │
                    ├──→ STAFF_PAYROLL (Many)
                    │       Monthly salary records
                    │
                    ├──→ TIMETABLES (Many) [Only for teachers]
                    │       Class schedules
                    │
                    ├──→ LESSON_PLANS (Many) [Only for teachers]
                    │       Lesson preparations
                    │
                    ├──→ ASSIGNMENTS (Many) [Only for teachers]
                    │       Given to classes
                    │
                    └──→ CONTINUOUS_ASSESSMENTS (Many) [Only for teachers]
                            Created tests/quizzes
```

### **STAFF Table** (Unified Employee Table)

```
┌────────────────────────────┐
│ STAFF                      │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • user_id (FK)            │  → USERS [1:1 relationship]
│ • department_id (FK)      │  → DEPARTMENTS (nullable)
│ • staff_no                │  e.g., "STAFF-2024-001" (Unique per school)
│ • staff_type (enum)       │  See staff types below
│ • qualification           │  e.g., "B.Ed Mathematics"
│ • specialization          │  e.g., "Secondary Education"
│ • employment_date         │  Date of joining
│ • years_of_experience     │  Prior experience
│ • previous_school         │  Previous employer
│ • office_location         │  e.g., "Staff Room A"
│ • emergency_contact       │  Phone number
│ • emergency_contact_      │  e.g., "Sister"
│   relationship            │
│ • contract_type           │  permanent/contract/temporary
│ • contract_start_date     │  For contract staff
│ • contract_end_date       │  Contract expiry
│ • salary (decimal)        │  Monthly salary
│ • bank_name               │  For payroll
│ • account_number          │  Bank account
│ • account_name            │  Account holder name
│ • tax_id                  │  Tax identification number
│ • pension_id              │  Pension fund number
│ • marital_status          │  single/married/divorced/widowed
│ • number_of_children      │  For records
│ • residential_address     │  Home address
│ • state_of_origin         │  Nigerian state
│ • lga                     │  Local Government Area
│ • nationality             │  Country
│ • religion                │  Optional
│ • blood_group             │  e.g., "O+", "A-"
│ • disabilities            │  Any special needs
│ • next_of_kin_name        │  Emergency contact
│ • next_of_kin_phone       │  Emergency phone
│ • next_of_kin_            │  e.g., "Spouse"
│   relationship            │
│ • highest_degree          │  e.g., "Masters"
│ • institution             │  Where obtained
│ • graduation_year         │  Year graduated
│ • professional_           │  e.g., "TRCN, NTI"
│   certifications          │
│ • languages_spoken        │  e.g., "English, Yoruba"
│ • status                  │  active/inactive/suspended/resigned
│ • deleted_at              │  Soft delete timestamp
│ • created_at, updated_at  │  Timestamps
└────────────────────────────┘
```

### **Staff Types Supported**

The `staff_type` enum field supports all employee categories:

```php
'staff_type' => [
    'Principal',              // School head
    'Vice Principal',         // Deputy head
    'Assistant Principal',    // Junior admin
    'Teacher',               // Teaching staff (most common)
    'Accountant',            // Finance officer
    'Bursar',                // Chief finance officer
    'Librarian',             // Library manager
    'Hostel Master',         // Boarding supervisor
    'Hostel Mistress',       // Boarding supervisor (female)
    'Driver',                // Transport staff
    'Nurse',                 // Medical staff
    'Lab Technician',        // Science lab staff
    'ICT Officer',           // IT support
    'Security',              // Security personnel
    'Cleaner',               // Janitorial staff
    'Gardener',              // Grounds keeping
    'Cook',                  // Cafeteria staff
    'Secretary',             // Administrative assistant
    'Registrar',             // Student records officer
    'Counselor',             // Guidance counselor
    'Other'                  // Any other employee type
]
```

**Key Benefits:**
- ✅ Single table for all employees (no duplicate tables)
- ✅ Easy to add new employee types (just update enum)
- ✅ Consistent data structure across all staff
- ✅ Unified payroll and HR management

### **TEACHER_SUBJECTS Table** (Teaching Assignments)

```
┌────────────────────────────┐
│ TEACHER_SUBJECTS           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • staff_id (FK)           │  → STAFF (must be staff_type='Teacher')
│ • class_id (FK)           │  → CLASSES
│ • subject_id (FK)         │  → SUBJECTS
│ • session_id (FK)         │  → ACADEMIC_SESSIONS
│ • term_id (FK)            │  → ACADEMIC_TERMS
│ • created_at, updated_at  │
│                            │
│ UNIQUE CONSTRAINT:         │
│ (school_id, staff_id,      │
│  class_id, subject_id,     │
│  session_id, term_id)      │
└────────────────────────────┘
```

**Purpose:** Links teachers to their teaching assignments
- One teacher can teach multiple subjects
- One teacher can teach multiple classes
- Assignments are per session and term

**Example:**
```
Mr. Adebayo (STAFF)
  teaches Mathematics (SUBJECT)
    to SS2A (CLASS)
      in 2024/2025 Session, 1st Term
```

### **STAFF_ATTENDANCE Table**

```
┌────────────────────────────┐
│ STAFF_ATTENDANCE           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • staff_id (FK)           │  → STAFF
│ • attendance_date         │  Date of attendance
│ • check_in                │  Time in
│ • check_out               │  Time out
│ • status                  │  present/absent/late/half_day
│ • late_by_minutes         │  If late, how many minutes
│ • remarks                 │  Optional notes
│ • marked_by               │  Who recorded (user_id)
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Purpose:** Daily attendance tracking for all staff

### **STAFF_LEAVE_REQUESTS Table**

```
┌────────────────────────────┐
│ STAFF_LEAVE_REQUESTS       │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • staff_id (FK)           │  → STAFF
│ • leave_type              │  annual/sick/maternity/casual
│ • start_date              │  Leave start
│ • end_date                │  Leave end
│ • number_of_days          │  Calculated days
│ • reason                  │  Leave reason
│ • supporting_documents    │  File path (optional)
│ • status                  │  pending/approved/rejected
│ • approved_by             │  Who approved (user_id)
│ • approval_date           │  When approved
│ • rejection_reason        │  If rejected, why
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Leave Types:**
- Annual Leave: Yearly vacation days
- Sick Leave: Medical reasons
- Maternity/Paternity Leave: Childbirth
- Casual Leave: Short notice leave
- Study Leave: For further education
- Compassionate Leave: Family emergencies

### **STAFF_PAYROLL Table**

```
┌────────────────────────────┐
│ STAFF_PAYROLL              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │  → SCHOOLS
│ • staff_id (FK)           │  → STAFF
│ • month                   │  Payment month (1-12)
│ • year                    │  Payment year
│ • basic_salary            │  Base salary
│ • allowances              │  JSON {housing: 5000, transport: 3000}
│ • bonuses                 │  Performance bonuses
│ • deductions              │  JSON {tax: 2000, pension: 1500}
│ • gross_salary            │  Before deductions
│ • net_salary              │  Take-home pay
│ • payment_method          │  bank_transfer/cash/cheque
│ • payment_date            │  When paid
│ • payment_reference       │  Bank reference number
│ • status                  │  pending/paid/failed
│ • paid_by                 │  Who processed (user_id)
│ • remarks                 │  Optional notes
│ • created_at, updated_at  │
└────────────────────────────┘
```

**Purpose:** Monthly salary records for all staff

**Business Rules:**
- One payroll record per staff per month
- Gross salary = basic_salary + allowances + bonuses
- Net salary = gross_salary - deductions
- Tracks payment history for audit

### **Relationships Summary:**

```
USERS (1:1) ↔ STAFF
    └──→ Authenticates as staff member

STAFF (Many) → DEPARTMENTS (1)
    └──→ Belongs to a department (optional)

DEPARTMENTS (1) → STAFF (1)
    └──→ Has head of department (optional)

STAFF (Many) ↔ SUBJECTS (Many) [via TEACHER_SUBJECTS]
    └──→ Teachers assigned to subjects

STAFF (Many) ↔ CLASSES (Many) [via TEACHER_SUBJECTS]
    └──→ Teachers assigned to classes

STAFF (1) → STAFF_ATTENDANCE (Many)
    └──→ Daily attendance records

STAFF (1) → STAFF_LEAVE_REQUESTS (Many)
    └──→ Leave applications

STAFF (1) → STAFF_PAYROLL (Many)
    └──→ Salary payment history
```

### **Key Improvements from Old System:**

**❌ Old System (Separate Tables):**
```
teachers table
principals table
accountants table (if added)
librarians table (if added)
... more duplicate tables
```

**✅ New System (Unified):**
```
staff table (all employees)
  staff_type field determines role
```

**Benefits:**
1. ✅ No data duplication
2. ✅ Easier to manage all employees
3. ✅ Single payroll system for everyone
4. ✅ Consistent attendance tracking
5. ✅ Scalable for new employee types
6. ✅ Industry standard architecture

---

## 👶 **5. STUDENT & GUARDIAN MANAGEMENT**

```
SCHOOLS (1) ──→ (Many) STUDENTS
CLASSES (1) ──→ (Many) STUDENTS
CLASS_ARMS (1) ──→ (Many) STUDENTS

┌────────────────────────────┐
│ STUDENTS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK) 🔒       │
│ • class_id (FK)           │
│ • arm_id (FK)             │
│ • admission_no (Unique)   │
│ • first_name              │
│ • last_name               │
│ • middle_name             │
│ • gender                  │
│ • date_of_birth           │
│ • blood_group             │
│ • address                 │
│ • state_of_origin         │
│ • religion                │
│ • profile_photo           │
│ • admission_date          │
│ • status (active/inactive)│
│ • deleted_at              │
└────────────────────────────┘

GUARDIANS (Many) ←──→ (Many) STUDENTS (Through GUARDIAN_STUDENTS)

┌────────────────────────────┐
│ GUARDIANS                  │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK) 🔒       │
│ • user_id (FK) [1:1]      │
│ • title                   │
│ • occupation              │
│ • employer                │
│ • work_address            │
│ • home_address            │
│ • status                  │
│ • deleted_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ GUARDIAN_STUDENTS          │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK) 🔒       │
│ • guardian_id (FK)        │
│ • student_id (FK)         │
│ • relationship            │
│ • is_primary              │
│ • is_emergency_contact    │
└────────────────────────────┘

┌────────────────────────────┐
│ STUDENT_DOCUMENTS          │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • document_type           │
│ • file_path               │
│ • uploaded_at             │
└────────────────────────────┘

┌────────────────────────────┐
│ STUDENT_MEDICAL_RECORDS    │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • ailment                 │
│ • allergies               │
│ • medications             │
│ • doctor_name             │
│ • hospital                │
│ • emergency_contact       │
└────────────────────────────┘

┌────────────────────────────┐
│ STUDENT_PROMOTIONS         │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • from_class_id (FK)      │
│ • to_class_id (FK)        │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • promoted_at             │
└────────────────────────────┘

┌────────────────────────────┐
│ STUDENT_TRANSFERS          │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • transfer_type           │
│ • destination_school      │
│ • transfer_date           │
│ • reason                  │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ ALUMNI                     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • graduation_year         │
│ • current_occupation      │
│ • employer                │
│ • contact_email           │
│ • contact_phone           │
└────────────────────────────┘
```

---

## 📚 **6. ATTENDANCE SYSTEM**

```
┌────────────────────────────┐
│ STUDENT_ATTENDANCE         │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK) 🔒       │
│ • student_id (FK)         │
│ • class_id (FK)           │
│ • date                    │
│ • status (present/absent/ │
│   late/excused)           │
│ • remarks                 │
│ • marked_by (teacher_id)  │
└────────────────────────────┘
```

---

## 📖 **7. ACADEMIC CONTENT & TIMETABLE**

```
┌────────────────────────────┐
│ TIMETABLES                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • class_id (FK)           │
│ • arm_id (FK)             │
│ • subject_id (FK)         │
│ • teacher_id (FK)         │
│ • day_of_week             │
│ • start_time              │
│ • end_time                │
│ • room                    │
└────────────────────────────┘

┌────────────────────────────┐
│ LESSON_PLANS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • teacher_id (FK)         │
│ • subject_id (FK)         │
│ • class_id (FK)           │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • title                   │
│ • objectives              │
│ • content                 │
│ • materials               │
│ • week_number             │
└────────────────────────────┘

┌────────────────────────────┐
│ LESSON_NOTES               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • lesson_plan_id (FK)     │
│ • content                 │
│ • attachments             │
│ • date                    │
└────────────────────────────┘

┌────────────────────────────┐
│ ASSIGNMENTS                │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • teacher_id (FK)         │
│ • subject_id (FK)         │
│ • class_id (FK)           │
│ • title                   │
│ • description             │
│ • due_date                │
│ • total_marks             │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ ASSIGNMENT_SUBMISSIONS     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • assignment_id (FK)      │
│ • student_id (FK)         │
│ • submission_text         │
│ • attachments             │
│ • submitted_at            │
│ • marks_obtained          │
│ • feedback                │
│ • graded_at               │
└────────────────────────────┘
```

---

## 📝 **8. ASSESSMENT & EXAMINATIONS**

```
CONTINUOUS_ASSESSMENTS (1) ──→ (Many) CA_QUESTIONS
CA_QUESTIONS (1) ──→ (Many) CA_QUESTION_OPTIONS
CA_QUESTIONS (1) ──→ (Many) CA_ANSWERS

┌────────────────────────────┐
│ CONTINUOUS_ASSESSMENTS     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • teacher_id (FK)         │
│ • subject_id (FK)         │
│ • class_id (FK)           │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • title                   │
│ • instructions            │
│ • duration_minutes        │
│ • total_marks             │
│ • passing_marks           │
│ • start_date              │
│ • end_date                │
└────────────────────────────┘

┌────────────────────────────┐
│ CA_QUESTIONS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • ca_id (FK)              │
│ • question_text           │
│ • question_type           │
│ • marks                   │
│ • order                   │
└────────────────────────────┘

┌────────────────────────────┐
│ CA_QUESTION_OPTIONS        │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • question_id (FK)        │
│ • option_text             │
│ • is_correct              │
│ • order                   │
└────────────────────────────┘

┌────────────────────────────┐
│ CA_ANSWERS                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • question_id (FK)        │
│ • student_id (FK)         │
│ • answer_text             │
│ • selected_option_id      │
│ • marks_obtained          │
│ • is_correct              │
└────────────────────────────┘

┌────────────────────────────┐
│ EXAMINATIONS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • name                    │
│ • exam_type               │
│ • start_date, end_date    │
└────────────────────────────┘

┌────────────────────────────┐
│ CBT_EXAMS                  │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • examination_id (FK)     │
│ • subject_id (FK)         │
│ • class_id (FK)           │
│ • title                   │
│ • duration_minutes        │
│ • total_marks             │
│ • passing_marks           │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ CBT_ATTEMPTS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • cbt_exam_id (FK)        │
│ • student_id (FK)         │
│ • started_at              │
│ • completed_at            │
│ • score                   │
│ • status                  │
└────────────────────────────┘
```

---

## 🎯 **9. RESULTS & GRADING**

```
┌────────────────────────────┐
│ EXAM_SCORES                │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • subject_id (FK)         │
│ • examination_id (FK)     │
│ • ca_score                │
│ • exam_score              │
│ • total_score             │
│ • grade                   │
└────────────────────────────┘

┌────────────────────────────┐
│ RESULTS                    │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • class_id (FK)           │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • total_score             │
│ • average                 │
│ • position                │
│ • remarks                 │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ RESULT_APPROVALS           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • result_id (FK)          │
│ • approved_by             │
│ • approved_at             │
│ • comments                │
└────────────────────────────┘

┌────────────────────────────┐
│ REPORT_CARDS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • generated_at            │
│ • file_path               │
└────────────────────────────┘

┌────────────────────────────┐
│ GRADING_SYSTEMS            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • is_default              │
└────────────────────────────┘

┌────────────────────────────┐
│ GRADE_SCALES               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • grading_system_id (FK)  │
│ • grade                   │
│ • min_score               │
│ • max_score               │
│ • remark                  │
│ • grade_point             │
└────────────────────────────┘

┌────────────────────────────┐
│ CERTIFICATES               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • certificate_type        │
│ • certificate_number      │
│ • issue_date              │
│ • file_path               │
└────────────────────────────┘
```

---

## 💰 **10. FEES & FINANCIAL MANAGEMENT**

```
FEE_CATEGORIES (1) ──→ (Many) FEE_STRUCTURES
FEE_STRUCTURES (1) ──→ (Many) INVOICES
INVOICES (1) ──→ (Many) INVOICE_ITEMS
INVOICES (1) ──→ (Many) PAYMENTS

┌────────────────────────────┐
│ FEE_CATEGORIES             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ FEE_STRUCTURES             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • category_id (FK)        │
│ • class_id (FK)           │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • amount                  │
└────────────────────────────┘

┌────────────────────────────┐
│ INVOICES                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • invoice_number (Unique) │
│ • session_id (FK)         │
│ • term_id (FK)            │
│ • total_amount            │
│ • paid_amount             │
│ • balance                 │
│ • due_date                │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ INVOICE_ITEMS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • invoice_id (FK)         │
│ • fee_structure_id (FK)   │
│ • description             │
│ • amount                  │
└────────────────────────────┘

┌────────────────────────────┐
│ PAYMENTS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • invoice_id (FK)         │
│ • student_id (FK)         │
│ • receipt_number (Unique) │
│ • amount                  │
│ • payment_method          │
│ • payment_date            │
│ • transaction_reference   │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ PAYMENT_TRANSACTIONS       │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • payment_id (FK)         │
│ • transaction_id          │
│ • gateway                 │
│ • status                  │
│ • response_data           │
└────────────────────────────┘

┌────────────────────────────┐
│ EXPENSE_CATEGORIES         │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ EXPENSES                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • category_id (FK)        │
│ • description             │
│ • amount                  │
│ • expense_date            │
│ • payment_method          │
│ • receipt                 │
└────────────────────────────┘
```

---

## 📚 **11. LIBRARY MANAGEMENT**

```
┌────────────────────────────┐
│ BOOK_CATEGORIES            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ LIBRARY_BOOKS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • category_id (FK)        │
│ • title                   │
│ • author                  │
│ • isbn                    │
│ • publisher               │
│ • publication_year        │
│ • quantity                │
│ • available_quantity      │
│ • shelf_location          │
└────────────────────────────┘

┌────────────────────────────┐
│ BORROWED_BOOKS             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • book_id (FK)            │
│ • student_id (FK)         │
│ • borrowed_date           │
│ • due_date                │
│ • return_date             │
│ • fine_amount             │
│ • status                  │
└────────────────────────────┘
```

---

## 🏨 **12. HOSTEL MANAGEMENT**

```
┌────────────────────────────┐
│ HOSTELS                    │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • hostel_type (boys/girls)│
│ • capacity                │
│ • warden_name             │
│ • address                 │
└────────────────────────────┘

┌────────────────────────────┐
│ HOSTEL_ROOMS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • hostel_id (FK)          │
│ • room_number             │
│ • capacity                │
│ • occupied                │
│ • floor                   │
└────────────────────────────┘

┌────────────────────────────┐
│ HOSTEL_ALLOCATIONS         │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • room_id (FK)            │
│ • session_id (FK)         │
│ • allocation_date         │
│ • status                  │
└────────────────────────────┘
```

---

## 🚌 **13. TRANSPORT MANAGEMENT**

```
┌────────────────────────────┐
│ TRANSPORT_ROUTES           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • route_name              │
│ • start_location          │
│ • end_location            │
│ • distance_km             │
│ • fare                    │
└────────────────────────────┘

┌────────────────────────────┐
│ TRANSPORT_VEHICLES         │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • vehicle_number          │
│ • vehicle_type            │
│ • capacity                │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ TRANSPORT_DRIVERS          │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • license_number          │
│ • phone                   │
│ • address                 │
└────────────────────────────┘

┌────────────────────────────┐
│ TRANSPORT_ASSIGNMENTS      │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • route_id (FK)           │
│ • vehicle_id (FK)         │
│ • driver_id (FK)          │
│ • pickup_point            │
│ • pickup_time             │
│ • status                  │
└────────────────────────────┘
```

---

## 🎭 **14. EXTRACURRICULAR ACTIVITIES**

```
┌────────────────────────────┐
│ SCHOOL_HOUSES              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • color                   │
│ • motto                   │
│ • points                  │
└────────────────────────────┘

┌────────────────────────────┐
│ STUDENT_HOUSES             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • student_id (FK)         │
│ • house_id (FK)           │
│ • assigned_date           │
└────────────────────────────┘

┌────────────────────────────┐
│ CLUBS                      │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • description             │
│ • coordinator_id          │
│ • meeting_day             │
│ • meeting_time            │
└────────────────────────────┘

┌────────────────────────────┐
│ CLUB_MEMBERS               │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • club_id (FK)            │
│ • student_id (FK)         │
│ • joined_date             │
│ • role                    │
└────────────────────────────┘

┌────────────────────────────┐
│ EVENTS                     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • title                   │
│ • description             │
│ • event_date              │
│ • start_time              │
│ • end_time                │
│ • venue                   │
│ • organizer               │
│ • status                  │
└────────────────────────────┘
```

---

## 📱 **15. COMMUNICATION SYSTEM**

```
┌────────────────────────────┐
│ NOTIFICATIONS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • user_id (FK)            │
│ • title                   │
│ • message                 │
│ • type                    │
│ • is_read                 │
│ • created_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ ANNOUNCEMENTS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • title                   │
│ • body                    │
│ • target_audience         │
│ • priority                │
│ • announced_at            │
│ • expires_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ MESSAGES                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • sender_id (FK)          │
│ • receiver_id (FK)        │
│ • subject                 │
│ • body                    │
│ • is_read                 │
│ • sent_at                 │
└────────────────────────────┘

┌────────────────────────────┐
│ SMS_QUEUE                  │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • recipient_phone         │
│ • message                 │
│ • status                  │
│ • scheduled_at            │
└────────────────────────────┘

┌────────────────────────────┐
│ SMS_LOGS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • recipient_phone         │
│ • message                 │
│ • status                  │
│ • sent_at                 │
│ • response                │
└────────────────────────────┘

┌────────────────────────────┐
│ EMAIL_QUEUE                │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • recipient_email         │
│ • subject                 │
│ • body                    │
│ • status                  │
│ • scheduled_at            │
└────────────────────────────┘

┌────────────────────────────┐
│ EMAIL_LOGS                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • recipient_email         │
│ • subject                 │
│ • body                    │
│ • status                  │
│ • sent_at                 │
│ • response                │
└────────────────────────────┘
```

---

## 🎓 **16. ADMISSION MANAGEMENT**

```
┌────────────────────────────┐
│ ADMISSION_APPLICATIONS     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • application_number      │
│ • first_name              │
│ • last_name               │
│ • date_of_birth           │
│ • gender                  │
│ • email                   │
│ • phone                   │
│ • guardian_name           │
│ • guardian_phone          │
│ • class_applied_for       │
│ • status                  │
│ • application_date        │
└────────────────────────────┘

┌────────────────────────────┐
│ ADMISSION_DOCUMENTS        │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • application_id (FK)     │
│ • document_type           │
│ • file_path               │
│ • uploaded_at             │
└────────────────────────────┘

┌────────────────────────────┐
│ ADMISSION_OFFERS           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • application_id (FK)     │
│ • offer_letter            │
│ • offered_date            │
│ • acceptance_deadline     │
│ • status                  │
└────────────────────────────┘
```

---

## 🏗️ **17. FACILITIES & INFRASTRUCTURE**

```
┌────────────────────────────┐
│ CLASSROOM_ROOMS            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • room_number             │
│ • capacity                │
│ • floor                   │
│ • equipment               │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ INVENTORY_CATEGORIES       │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ INVENTORY_ITEMS            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • category_id (FK)        │
│ • name                    │
│ • quantity                │
│ • unit_price              │
│ • total_value             │
│ • location                │
└────────────────────────────┘

┌────────────────────────────┐
│ INVENTORY_TRANSACTIONS     │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • item_id (FK)            │
│ • transaction_type        │
│ • quantity                │
│ • transaction_date        │
│ • performed_by            │
│ • remarks                 │
└────────────────────────────┘

┌────────────────────────────┐
│ VISITORS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • phone                   │
│ • purpose                 │
│ • person_to_meet          │
│ • check_in_time           │
│ • check_out_time          │
│ • id_type                 │
│ • id_number               │
└────────────────────────────┘
```

---

## ⚙️ **18. SYSTEM ADMINISTRATION**

```
┌────────────────────────────┐
│ SETTINGS                   │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • key                     │
│ • value                   │
│ • type                    │
└────────────────────────────┘

┌────────────────────────────┐
│ AUDIT_LOGS                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • user_id (FK)            │
│ • action                  │
│ • model                   │
│ • model_id                │
│ • old_values              │
│ • new_values              │
│ • ip_address              │
│ • user_agent              │
│ • created_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ ACTIVITY_LOGS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • user_id (FK)            │
│ • description             │
│ • subject_type            │
│ • subject_id              │
│ • properties              │
│ • created_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ DATABASE_BACKUPS           │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • file_name               │
│ • file_path               │
│ • file_size               │
│ • backup_type             │
│ • status                  │
│ • created_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ API_TOKENS                 │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • user_id (FK)            │
│ • name                    │
│ • token                   │
│ • abilities               │
│ • last_used_at            │
│ • expires_at              │
└────────────────────────────┘

┌────────────────────────────┐
│ LOGIN_SESSIONS             │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • user_id (FK)            │
│ • ip_address              │
│ • user_agent              │
│ • login_at                │
│ • logout_at               │
└────────────────────────────┘

┌────────────────────────────┐
│ SYSTEM_CONFIGURATIONS      │
├────────────────────────────┤
│ • id (PK)                 │
│ • key                     │
│ • value                   │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ SYSTEM_MODULES             │
├────────────────────────────┤
│ • id (PK)                 │
│ • name                    │
│ • slug                    │
│ • description             │
│ • is_enabled              │
│ • version                 │
└────────────────────────────┘
```

---

## 📊 **19. SUBSCRIPTION & BILLING (Multi-School)**

```
┌────────────────────────────┐
│ SUBSCRIPTIONS              │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • plan_name               │
│ • start_date              │
│ • end_date                │
│ • amount                  │
│ • status                  │
└────────────────────────────┘

┌────────────────────────────┐
│ SUBSCRIPTION_PAYMENTS      │
├────────────────────────────┤
│ • id (PK)                 │
│ • subscription_id (FK)    │
│ • amount                  │
│ • payment_date            │
│ • payment_method          │
│ • transaction_id          │
│ • status                  │
└────────────────────────────┘
```

---

## 📅 **20. CALENDAR & MEETINGS**

```
┌────────────────────────────┐
│ SCHOOL_HOLIDAYS            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • title                   │
│ • start_date              │
│ • end_date                │
│ • description             │
└────────────────────────────┘

┌────────────────────────────┐
│ PARENT_MEETINGS            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • title                   │
│ • meeting_date            │
│ • meeting_time            │
│ • venue                   │
│ • agenda                  │
│ • attendees               │
└────────────────────────────┘

┌────────────────────────────┐
│ SCHOOL_BRANCHES            │
├────────────────────────────┤
│ • id (PK)                 │
│ • school_id (FK)          │
│ • name                    │
│ • address                 │
│ • phone                   │
│ • email                   │
│ • manager_name            │
└────────────────────────────┘
```

---

## 🔗 **RELATIONSHIP SUMMARY**

### **Complete Foreign Key Map**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    PRIMARY FOREIGN KEY RELATIONSHIPS                        │
└────────────────────────────────────────────────────────────────────────────┘

ALL TABLES (98)
    ├─ school_id → SCHOOLS.id [Index for fast filtering]
    
USERS
    ├─ school_id → SCHOOLS.id
    └─ role_id → ROLES.id

ROLES
    └─ school_id → SCHOOLS.id

PERMISSIONS
    └─ school_id → SCHOOLS.id

ROLE_PERMISSIONS
    ├─ school_id → SCHOOLS.id
    ├─ role_id → ROLES.id
    └─ permission_id → PERMISSIONS.id

STAFF (Replaces old TEACHERS table)
    ├─ school_id → SCHOOLS.id
    ├─ user_id → USERS.id [1:1 relationship]
    └─ department_id → DEPARTMENTS.id [nullable]

GUARDIANS
    ├─ school_id → SCHOOLS.id
    └─ user_id → USERS.id [1:1 relationship]

DEPARTMENTS
    ├─ school_id → SCHOOLS.id
    └─ head_staff_id → STAFF.id [nullable]

CLASSES
    ├─ school_id → SCHOOLS.id
    └─ department_id → DEPARTMENTS.id [nullable for junior classes]

CLASS_ARMS
    ├─ school_id → SCHOOLS.id
    ├─ class_id → CLASSES.id
    └─ staff_id → STAFF.id [nullable, Form Teacher]

SUBJECTS
    ├─ school_id → SCHOOLS.id
    └─ department_id → DEPARTMENTS.id [nullable]

STUDENTS
    ├─ school_id → SCHOOLS.id
    ├─ class_id → CLASSES.id
    └─ arm_id → CLASS_ARMS.id

ACADEMIC_SESSIONS
    └─ school_id → SCHOOLS.id

ACADEMIC_TERMS
    ├─ school_id → SCHOOLS.id
    └─ session_id → ACADEMIC_SESSIONS.id

TEACHER_SUBJECTS [Junction Table - Many:Many]
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id (must be staff_type='Teacher')
    ├─ class_id → CLASSES.id
    ├─ subject_id → SUBJECTS.id
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id
    UNIQUE: (school_id, staff_id, class_id, subject_id, session_id, term_id)

GUARDIAN_STUDENTS [Junction Table - Many:Many]
    ├─ school_id → SCHOOLS.id
    ├─ guardian_id → GUARDIANS.id
    └─ student_id → STUDENTS.id

INVOICES
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id

PAYMENTS
    ├─ school_id → SCHOOLS.id
    ├─ invoice_id → INVOICES.id
    └─ student_id → STUDENTS.id

EXAM_SCORES
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ subject_id → SUBJECTS.id
    └─ examination_id → EXAMINATIONS.id

RESULTS
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ class_id → CLASSES.id
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id

CONTINUOUS_ASSESSMENTS
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id (teacher who created it)
    ├─ subject_id → SUBJECTS.id
    ├─ class_id → CLASSES.id
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id

ASSIGNMENTS
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id (teacher who created it)
    ├─ subject_id → SUBJECTS.id
    └─ class_id → CLASSES.id

STUDENT_ATTENDANCE
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ class_id → CLASSES.id
    └─ marked_by → STAFF.id (teacher who marked)

LIBRARY_BOOKS
    ├─ school_id → SCHOOLS.id
    └─ category_id → BOOK_CATEGORIES.id

BORROWED_BOOKS
    ├─ school_id → SCHOOLS.id
    ├─ book_id → LIBRARY_BOOKS.id
    └─ student_id → STUDENTS.id

HOSTEL_ALLOCATIONS
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ room_id → HOSTEL_ROOMS.id
    └─ session_id → ACADEMIC_SESSIONS.id

TRANSPORT_ASSIGNMENTS
    ├─ school_id → SCHOOLS.id
    ├─ student_id → STUDENTS.id
    ├─ route_id → TRANSPORT_ROUTES.id
    ├─ vehicle_id → TRANSPORT_VEHICLES.id
    └─ driver_id → TRANSPORT_DRIVERS.id

STAFF_ATTENDANCE
    ├─ school_id → SCHOOLS.id
    └─ staff_id → STAFF.id

STAFF_LEAVE_REQUESTS
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id
    └─ approved_by → USERS.id

STAFF_PAYROLL
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id
    └─ paid_by → USERS.id

TIMETABLES
    ├─ school_id → SCHOOLS.id
    ├─ class_id → CLASSES.id
    ├─ arm_id → CLASS_ARMS.id
    ├─ subject_id → SUBJECTS.id
    ├─ staff_id → STAFF.id (teacher)
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id

LESSON_PLANS
    ├─ school_id → SCHOOLS.id
    ├─ staff_id → STAFF.id (teacher)
    ├─ subject_id → SUBJECTS.id
    ├─ class_id → CLASSES.id
    ├─ session_id → ACADEMIC_SESSIONS.id
    └─ term_id → ACADEMIC_TERMS.id
```

### **Relationship Type Summary**

| Relationship Type | Count | Examples |
|------------------|-------|----------|
| **1:1** | 2 | USERS ↔ STAFF, USERS ↔ GUARDIANS |
| **1:Many** | 250+ | SCHOOLS → USERS, CLASSES → STUDENTS, INVOICES → PAYMENTS |
| **Many:Many** | 5 | STAFF ↔ SUBJECTS, GUARDIANS ↔ STUDENTS, CLUBS ↔ STUDENTS |
| **Self-Referencing** | 2 | DEPARTMENTS.head_staff_id, CLASS_ARMS.staff_id |

### **Junction Tables (Many-to-Many Relationships)**

| Junction Table | Links | Purpose |
|----------------|-------|---------|
| **ROLE_PERMISSIONS** | ROLES ↔ PERMISSIONS | Define role access rights |
| **TEACHER_SUBJECTS** | STAFF ↔ SUBJECTS ↔ CLASSES | Teaching assignments (staff_type='Teacher') |
| **GUARDIAN_STUDENTS** | GUARDIANS ↔ STUDENTS | Parent-child relationships |
| **CLUB_MEMBERS** | CLUBS ↔ STUDENTS | Club participation |
| **STUDENT_HOUSES** | SCHOOL_HOUSES ↔ STUDENTS | House system allocation |

---

## 📊 **DATA FLOW EXAMPLES**

### **Example 1: Teacher Assignment Flow**

```
SCENARIO: Assign Mr. Adebayo to teach Mathematics to SS2A for Term 1, 2024/2025

1. Check Prerequisites:
   SCHOOLS.id = 1
   USERS.id = 10 (Mr. Adebayo's user account)
   STAFF.id = 5 (linked to user_id = 10, staff_type = 'Teacher')
   SUBJECTS.id = 3 (Mathematics)
   CLASSES.id = 8 (SS2)
   CLASS_ARMS.id = 12 (SS2A)
   ACADEMIC_SESSIONS.id = 2 (2024/2025)
   ACADEMIC_TERMS.id = 4 (1st Term)

2. Create Assignment:
   INSERT INTO TEACHER_SUBJECTS (
       school_id, staff_id, class_id, subject_id, session_id, term_id
   ) VALUES (
       1, 5, 8, 3, 2, 4
   )

3. Result:
   - Mr. Adebayo can now mark attendance for SS2A Mathematics
   - Can create assignments for SS2A Mathematics
   - Can record CA scores for SS2A Mathematics students
   - Appears on SS2A timetable

4. Validation:
   UNIQUE constraint prevents duplicate assignment
```

### **Example 2: Student Enrollment & Guardian Linking**

```
SCENARIO: Enroll new student Ada Johnson in JSS2A, link to father Mr. Johnson

1. Create User Account for Guardian:
   INSERT INTO USERS (school_id, role_id, first_name, last_name, email)
   VALUES (1, 5, 'John', 'Johnson', 'john.johnson@email.com')
   → USERS.id = 25

2. Create Guardian Profile:
   INSERT INTO GUARDIANS (school_id, user_id, occupation)
   VALUES (1, 25, 'Engineer')
   → GUARDIANS.id = 8

3. Create Student Record:
   INSERT INTO STUDENTS (
       school_id, class_id, arm_id, first_name, last_name,
       admission_no, date_of_birth, gender
   ) VALUES (
       1, 4, 6, 'Ada', 'Johnson', 'STU-2024-001',
       '2010-05-15', 'Female'
   )
   → STUDENTS.id = 150

4. Link Guardian to Student:
   INSERT INTO GUARDIAN_STUDENTS (
       school_id, guardian_id, student_id, relationship, is_primary
   ) VALUES (
       1, 8, 150, 'Father', true
   )

5. Result:
   - Mr. Johnson can now log in as Guardian
   - Can view Ada's attendance, results, fees
   - Receives notifications about Ada
   - Can make fee payments for Ada
```

### **Example 3: Invoice Generation & Payment**

```
SCENARIO: Generate termly invoice for Ada Johnson, record payment

1. Check Fee Structure:
   SELECT amount FROM FEE_STRUCTURES
   WHERE school_id = 1
     AND class_id = 4 (JSS2)
     AND session_id = 2
     AND term_id = 4
   Result:
   - Tuition: ₦50,000
   - Books: ₦5,000
   - Development: ₦10,000
   Total: ₦65,000

2. Generate Invoice:
   INSERT INTO INVOICES (
       school_id, student_id, invoice_number, session_id, term_id,
       total_amount, paid_amount, balance, status
   ) VALUES (
       1, 150, 'INV-2024-001', 2, 4,
       65000, 0, 65000, 'pending'
   )
   → INVOICES.id = 200

3. Add Invoice Items:
   INSERT INTO INVOICE_ITEMS (school_id, invoice_id, description, amount)
   VALUES
       (1, 200, 'Tuition Fee', 50000),
       (1, 200, 'Books', 5000),
       (1, 200, 'Development Levy', 10000)

4. Guardian Makes Payment:
   INSERT INTO PAYMENTS (
       school_id, invoice_id, student_id, receipt_number,
       amount, payment_method, payment_date, status
   ) VALUES (
       1, 200, 150, 'RCT-2024-001',
       30000, 'bank_transfer', '2024-09-15', 'confirmed'
   )

5. Update Invoice:
   UPDATE INVOICES
   SET paid_amount = 30000,
       balance = 35000,
       status = 'partial'
   WHERE id = 200

6. Result:
   - Invoice balance: ₦35,000
   - Payment receipt generated
   - Email/SMS sent to guardian
   - Payment recorded in financial reports
```

### **Example 4: Assessment & Result Computation**

```
SCENARIO: Record CA test, exam score, compute result for Ada

1. Teacher Creates CA Test:
   INSERT INTO CONTINUOUS_ASSESSMENTS (
       school_id, teacher_id, subject_id, class_id, session_id, term_id,
       title, total_marks
   ) VALUES (
       1, 5, 3, 8, 2, 4,
       'Mid-Term Mathematics Test', 20
   )
   → CA.id = 50

2. Add Questions:
   INSERT INTO CA_QUESTIONS (school_id, ca_id, question_text, marks)
   VALUES (1, 50, 'Solve: 2x + 5 = 15', 5)

3. Student Answers:
   INSERT INTO CA_ANSWERS (
       school_id, question_id, student_id, answer_text, marks_obtained
   ) VALUES (1, 101, 150, 'x = 5', 5)

4. Record Exam Score:
   INSERT INTO EXAM_SCORES (
       school_id, student_id, subject_id, examination_id,
       ca_score, exam_score, total_score
   ) VALUES (
       1, 150, 3, 10,
       18, 65, 83
   )
   -- CA: 18/20, Exam: 65/80, Total: 83/100

5. Compute Overall Result:
   -- Aggregate all subject scores
   INSERT INTO RESULTS (
       school_id, student_id, class_id, session_id, term_id,
       total_score, average, position, remarks
   ) VALUES (
       1, 150, 4, 2, 4,
       750, 75.0, 3, 'Good performance'
   )

6. Generate Report Card:
   INSERT INTO REPORT_CARDS (
       school_id, student_id, session_id, term_id, file_path
   ) VALUES (
       1, 150, 2, 4, 'reports/2024-2025-term1-student150.pdf'
   )

7. Approve Result:
   INSERT INTO RESULT_APPROVALS (
       school_id, result_id, approved_by, approved_at
   ) VALUES (
       1, 75, 2, NOW()) -- Approved by Principal

8. Result:
   - Ada's result: 75% average, 3rd position
   - Report card PDF generated
   - Guardian can view/download result
   - Result appears in analytics dashboard
```

### **Example 5: Library Book Borrowing**

```
SCENARIO: Ada borrows "Things Fall Apart" from library

1. Check Book Availability:
   SELECT available_quantity FROM LIBRARY_BOOKS
   WHERE id = 45 AND school_id = 1
   Result: 3 copies available

2. Record Borrowing:
   INSERT INTO BORROWED_BOOKS (
       school_id, book_id, student_id,
       borrowed_date, due_date, status
   ) VALUES (
       1, 45, 150,
       '2024-10-01', '2024-10-15', 'borrowed'
   )
   → BORROWED_BOOKS.id = 88

3. Update Book Quantity:
   UPDATE LIBRARY_BOOKS
   SET available_quantity = available_quantity - 1
   WHERE id = 45 AND school_id = 1
   Result: 2 copies now available

4. Student Returns Book (2 days late):
   UPDATE BORROWED_BOOKS
   SET return_date = '2024-10-17',
       fine_amount = 200, -- ₦100 per day
       status = 'returned'
   WHERE id = 88

5. Update Book Quantity:
   UPDATE LIBRARY_BOOKS
   SET available_quantity = available_quantity + 1
   WHERE id = 45
   Result: 3 copies available again

6. Result:
   - Book borrowed and returned successfully
   - Fine of ₦200 recorded
   - Available quantity restored
   - Borrowing history maintained
```

---

## 🔒 **MULTI-TENANT SECURITY**

### **Data Isolation Strategy**

Every query MUST filter by `school_id` to ensure complete data isolation:

```sql
-- ✅ CORRECT: Filtered by school_id
SELECT * FROM students
WHERE school_id = 1 AND class_id = 5;

-- ❌ WRONG: No school_id filter (security risk!)
SELECT * FROM students
WHERE class_id = 5;
```

### **Laravel Query Scoping Example**

```php
// Automatic school scoping in Laravel models
protected static function booted()
{
    static::addGlobalScope('school', function (Builder $query) {
        $query->where('school_id', auth()->user()->school_id);
    });
}

// All queries now automatically filter by school
Student::all(); // Only returns current school's students
Invoice::find($id); // Only finds invoice from current school
```

### **Unique Constraints with School Context**

```sql
-- Email unique per school (not globally)
UNIQUE KEY `unique_user_email` (`school_id`, `email`)

-- Student admission number unique per school
UNIQUE KEY `unique_admission_no` (`school_id`, `admission_no`)

-- Invoice number unique per school
UNIQUE KEY `unique_invoice_number` (`school_id`, `invoice_number`)

-- Teacher assignment unique
UNIQUE KEY `unique_teacher_assignment` (
    `school_id`, `teacher_id`, `class_id`, 
    `subject_id`, `session_id`, `term_id`
)
```

### **Row-Level Security Checks**

```php
// Validation Rules with School Context
Rule::exists('classes', 'id')
    ->where('school_id', auth()->user()->school_id);

Rule::exists('students', 'id')
    ->where('school_id', auth()->user()->school_id)
    ->where('class_id', $request->class_id);

// Ensures selected records belong to current school
```

---

## 📈 **DATABASE STATISTICS**


| Category | Tables | Description |
|----------|--------|-------------|
| **Core System** | 10 | Schools, Users, Roles, Permissions, Sessions |
| **Academic** | 15 | Classes, Subjects, Departments, Sessions, Terms |
| **Staff** | 5 | Teachers, Attendance, Leave, Payroll, Subjects |
| **Students** | 12 | Students, Guardians, Documents, Medical, Promotions |
| **Attendance** | 2 | Student & Teacher Attendance |
| **Teaching** | 8 | Timetables, Lessons, Assignments, Submissions |
| **Assessments** | 10 | CA, CBT, Examinations, Scores, Results |
| **Grading** | 4 | Grading Systems, Scales, Report Cards, Certificates |
| **Finance** | 12 | Fees, Invoices, Payments, Expenses, Payroll |
| **Library** | 3 | Books, Categories, Borrowed Books |
| **Hostel** | 3 | Hostels, Rooms, Allocations |
| **Transport** | 4 | Routes, Vehicles, Drivers, Assignments |
| **Activities** | 5 | Houses, Clubs, Events |
| **Communication** | 7 | Notifications, SMS, Email, Messages, Announcements |
| **Admission** | 3 | Applications, Documents, Offers |
| **Facilities** | 6 | Classrooms, Inventory, Visitors |
| **System** | 9 | Settings, Logs, Backups, API, Modules |
| **Total** | **98** | Complete School Management System |

---

## 🎯 **DESIGN PRINCIPLES**

### **1. Multi-Tenancy First**
Every table scoped to `school_id` for complete isolation

### **2. Soft Deletes**
Key tables use `deleted_at` for data recovery:
- Users, Teachers, Students, Guardians

### **3. Timestamps**
All tables have `created_at` and `updated_at`

### **4. UUID Support**
Schools and Users have UUID for public identification

### **5. Status Fields**
Most entities have `status` (active/inactive/suspended)

### **6. Audit Trail**
Complete activity and audit logging system

### **7. Flexible Relationships**
Many-to-many through pivot tables with additional metadata

---

## 🌍 **WEST AFRICAN CONTEXT**

### **Curriculum Agnostic**
Supports multiple West African curricula:
- 🇳🇬 Nigeria: WAEC, NECO, BECE
- 🇬🇭 Ghana: WASSCE, BECE
- 🇸🇱 Sierra Leone: WASSCE
- 🇱🇷 Liberia: WASSCE
- 🇬🇲 The Gambia: GABECE, WASSCE

### **Academic Structure**
- **Junior Secondary**: JSS1, JSS2, JSS3
- **Senior Secondary**: SS1, SS2, SS3
- **Departments**: Sciences, Arts, Commercial
- **Terms**: 3 terms per academic session

---

## 📝 **NOTES**

1. **🔒 Security Score**: 92% (Multi-tenant isolation implemented)
2. **💾 Total Tables**: 98 comprehensive tables
3. **🔗 Foreign Keys**: All relationships properly constrained
4. **📊 Indexes**: school_id indexed on all operational tables
5. **🚀 Performance**: Optimized for multi-school queries

---

**Generated**: 2026-07-13  
**Version**: 1.0  
**System**: School Management System (SMS) for West Africa
