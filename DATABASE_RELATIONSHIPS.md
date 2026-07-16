# 🔗 School Management System - Database Relationships Map

## **Visual Entity Relationship Diagram**

---

## 🏢 **CORE MULTI-TENANT STRUCTURE**

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              🏢 SCHOOLS (Root)                              │
│                                    id                                        │
└────────────────┬────────────────────────────────────────────────────────────┘
                 │
                 │ school_id (FK) - All tables branch from here
                 │
     ┌───────────┼───────────┬───────────┬───────────┬───────────┬──────────┐
     │           │           │           │           │           │          │
     ▼           ▼           ▼           ▼           ▼           ▼          ▼
  USERS     ACADEMIC_   DEPARTMENTS  CLASSES   FEE_        SETTINGS    And 90+
            SESSIONS                          CATEGORIES               more...
```

---

## 👥 **USER AUTHENTICATION & AUTHORIZATION CHAIN**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         USER IDENTITY & ACCESS                              │
└────────────────────────────────────────────────────────────────────────────┘

    SCHOOLS (1)
        │
        │ school_id
        ▼
    ROLES (Many)                    PERMISSIONS (Many)
        │                                  │
        │ role_id                         │ permission_id
        │                                  │
        └──────────┬─────────────────────┘
                   │
                   ▼
         ROLE_PERMISSIONS (Junction)
              school_id, role_id, permission_id
                   │
                   │
    ┌──────────────┴──────────────┐
    │                             │
    ▼                             │
  USERS (Many)                    │
    id, school_id, role_id        │
    │                             │
    ├─────────────────────────────┘
    │
    ├──→ TEACHERS (1:1 via user_id)
    │       └──→ TEACHER_SUBJECTS (Many)
    │       └──→ TEACHER_ATTENDANCE (Many)
    │       └──→ STAFF_LEAVE_REQUESTS (Many)
    │       └──→ TEACHER_PAYROLL (Many)
    │
    ├──→ GUARDIANS (1:1 via user_id)
    │       └──→ GUARDIAN_STUDENTS (Many)
    │
    └──→ LOGIN_SESSIONS (Many via user_id)
    └──→ NOTIFICATIONS (Many via user_id)
    └──→ ACTIVITY_LOGS (Many via user_id)
    └──→ AUDIT_LOGS (Many via user_id)
```

**Key Relationships:**
- 1 School → Many Roles
- 1 School → Many Permissions
- Many Roles ↔ Many Permissions (via ROLE_PERMISSIONS)
- 1 Role → Many Users
- 1 User → 1 Teacher (optional)
- 1 User → 1 Guardian (optional)

---

## 🎓 **ACADEMIC HIERARCHY**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         ACADEMIC STRUCTURE TREE                             │
└────────────────────────────────────────────────────────────────────────────┘

SCHOOLS (1)
    │
    ├──→ ACADEMIC_SESSIONS (Many)
    │       │ id, school_id
    │       │
    │       └──→ ACADEMIC_TERMS (Many)
    │               id, school_id, session_id
    │               │
    │               └──→ Used in: INVOICES, RESULTS, FEE_STRUCTURES,
    │                             EXAMINATIONS, CONTINUOUS_ASSESSMENTS
    │
    ├──→ DEPARTMENTS (Many)
    │       │ id, school_id, head_teacher_id (FK → TEACHERS)
    │       │
    │       ├──→ CLASSES (Many)
    │       │       │ id, school_id, department_id
    │       │       │
    │       │       ├──→ CLASS_ARMS (Many)
    │       │       │       id, school_id, class_id, teacher_id (FK → TEACHERS)
    │       │       │       │
    │       │       │       └──→ STUDENTS (Many via arm_id)
    │       │       │
    │       │       └──→ STUDENTS (Many via class_id)
    │       │       └──→ TIMETABLES (Many via class_id)
    │       │       └──→ TEACHER_SUBJECTS (Many via class_id)
    │       │       └──→ ASSIGNMENTS (Many via class_id)
    │       │       └──→ CONTINUOUS_ASSESSMENTS (Many via class_id)
    │       │       └──→ CBT_EXAMS (Many via class_id)
    │       │
    │       ├──→ SUBJECTS (Many)
    │       │       id, school_id, department_id
    │       │       │
    │       │       └──→ TEACHER_SUBJECTS (Many via subject_id)
    │       │       └──→ ASSIGNMENTS (Many via subject_id)
    │       │       └──→ EXAM_SCORES (Many via subject_id)
    │       │       └──→ CONTINUOUS_ASSESSMENTS (Many via subject_id)
    │       │
    │       └──→ TEACHERS (Many)
    │               id, school_id, department_id, user_id (FK → USERS)
    │
    └──→ TEACHERS (Many - can be without department)
            id, school_id, user_id, department_id (nullable)
```

**Key Relationships:**
- 1 School → Many Academic Sessions → Many Terms
- 1 School → Many Departments
- 1 Department → Many Classes
- 1 Class → Many Class Arms
- 1 Class Arm → Many Students
- 1 Department → Many Subjects
- 1 Department → 1 Head Teacher (optional)
- 1 Class Arm → 1 Form Teacher (optional)

---

## 👨‍🏫 **TEACHER ASSIGNMENT SYSTEM**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    TEACHER-SUBJECT-CLASS RELATIONSHIP                       │
└────────────────────────────────────────────────────────────────────────────┘

TEACHERS (Many)
    │ id, school_id, user_id, department_id
    │
    └──→ TEACHER_SUBJECTS (Junction Table - Many:Many)
            │ id, school_id, teacher_id, class_id, subject_id, 
            │    session_id, term_id
            │
            ├──→ Links to: CLASSES (via class_id)
            ├──→ Links to: SUBJECTS (via subject_id)
            ├──→ Links to: ACADEMIC_SESSIONS (via session_id)
            └──→ Links to: ACADEMIC_TERMS (via term_id)

Example:
    Mr. Adebayo (TEACHER)
        teaches
            - Mathematics (SUBJECT) 
            - in SS2A (CLASS + ARM)
            - during 2024/2025 Session, 1st Term

Unique Constraint:
    (school_id, teacher_id, class_id, subject_id, session_id, term_id)
    - Prevents duplicate assignments
```

---

## 👶 **STUDENT & GUARDIAN NETWORK**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    STUDENT-GUARDIAN RELATIONSHIP                            │
└────────────────────────────────────────────────────────────────────────────┘

CLASSES (1)
    │
    └──→ CLASS_ARMS (Many)
            │
            └──→ STUDENTS (Many)
                    │ id, school_id, class_id, arm_id
                    │
                    ├──→ GUARDIAN_STUDENTS (Many) ←──┐
                    │       │ school_id, guardian_id, │
                    │       │ student_id, relationship│
                    │       │                         │
                    │       └─────────────────────────┤
                    │                                 │
                    ├──→ STUDENT_ATTENDANCE (Many)   │
                    ├──→ EXAM_SCORES (Many)          │
                    ├──→ RESULTS (Many)              │
                    ├──→ INVOICES (Many)             │
                    ├──→ PAYMENTS (Many)             │
                    ├──→ ASSIGNMENT_SUBMISSIONS      │
                    ├──→ STUDENT_DOCUMENTS (Many)    │
                    ├──→ STUDENT_MEDICAL_RECORDS     │
                    ├──→ STUDENT_PROMOTIONS (Many)   │
                    ├──→ STUDENT_TRANSFERS (Many)    │
                    ├──→ BORROWED_BOOKS (Many)       │
                    ├──→ HOSTEL_ALLOCATIONS (Many)   │
                    ├──→ TRANSPORT_ASSIGNMENTS       │
                    ├──→ CLUB_MEMBERS (Many)         │
                    ├──→ STUDENT_HOUSES (Many)       │
                    └──→ CBT_ATTEMPTS (Many)         │
                                                      │
GUARDIANS (Many) ─────────────────────────────────────┘
    │ id, school_id, user_id
    │
    └──→ Can have multiple students via GUARDIAN_STUDENTS

Example:
    Mr. Johnson (GUARDIAN)
        is father to:
            - Ada Johnson (STUDENT in JSS2A)
            - Chike Johnson (STUDENT in SS1B)
```

---

## 📚 **TEACHING & LEARNING WORKFLOW**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                      TEACHING ACTIVITIES FLOW                               │
└────────────────────────────────────────────────────────────────────────────┘

TIMETABLES
    │ school_id, class_id, arm_id, subject_id, teacher_id
    │ day_of_week, start_time, end_time
    │
    └──→ Defines: When & Where teaching happens

LESSON_PLANS
    │ school_id, teacher_id, subject_id, class_id, 
    │ session_id, term_id
    │
    ├──→ LESSON_NOTES (Many)
    │       teacher's notes for the lesson
    │
    └──→ Used to plan weekly teaching

ASSIGNMENTS
    │ school_id, teacher_id, subject_id, class_id
    │
    └──→ ASSIGNMENT_SUBMISSIONS (Many)
            │ school_id, assignment_id, student_id
            │
            └──→ Each student submits independently
```

---

## 📝 **ASSESSMENT & EXAMINATION SYSTEM**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                    CONTINUOUS ASSESSMENT (CA) FLOW                          │
└────────────────────────────────────────────────────────────────────────────┘

CONTINUOUS_ASSESSMENTS (Test/Quiz)
    │ id, school_id, teacher_id, subject_id, class_id,
    │ session_id, term_id
    │
    └──→ CA_QUESTIONS (Many)
            │ id, school_id, ca_id, question_text, marks
            │
            ├──→ CA_QUESTION_OPTIONS (Many)
            │       │ id, school_id, question_id, 
            │       │ option_text, is_correct
            │       │
            │       └──→ For multiple choice questions
            │
            └──→ CA_ANSWERS (Many)
                    │ id, school_id, question_id, student_id,
                    │ answer_text, selected_option_id, marks_obtained
                    │
                    └──→ Each student's answer per question


┌────────────────────────────────────────────────────────────────────────────┐
│                         CBT EXAMINATION FLOW                                │
└────────────────────────────────────────────────────────────────────────────┘

EXAMINATIONS (Exam Period)
    │ id, school_id, session_id, term_id
    │ name (e.g., "Mid-Term Exam")
    │
    └──→ CBT_EXAMS (Many)
            │ id, school_id, examination_id, subject_id, class_id
            │
            └──→ CBT_ATTEMPTS (Many)
                    id, school_id, cbt_exam_id, student_id, score


┌────────────────────────────────────────────────────────────────────────────┐
│                         GRADING & RESULTS FLOW                              │
└────────────────────────────────────────────────────────────────────────────┘

EXAM_SCORES (Individual Subject Score)
    │ id, school_id, student_id, subject_id, examination_id
    │ ca_score, exam_score, total_score, grade
    │
    └──→ Aggregated into ──→ RESULTS (Overall Performance)
                               │ id, school_id, student_id, class_id,
                               │ session_id, term_id, total_score, 
                               │ average, position
                               │
                               ├──→ RESULT_APPROVALS (Many)
                               │       approval workflow
                               │
                               └──→ REPORT_CARDS (Generated PDF)
                                       stored file_path

GRADING_SYSTEMS
    │ id, school_id, name, is_default
    │
    └──→ GRADE_SCALES (Many)
            id, school_id, grading_system_id, grade,
            min_score, max_score, remark, grade_point
            
            Example:
            A: 70-100 (Excellent) - 5.0
            B: 60-69 (Very Good) - 4.0
            C: 50-59 (Good) - 3.0
```

---

## 💰 **FINANCIAL MANAGEMENT FLOW**

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         FEE MANAGEMENT WORKFLOW                             │
└────────────────────────────────────────────────────────────────────────────┘

FEE_CATEGORIES (Types of Fees)
    │ id, school_id, name (e.g., Tuition, Transport, Books)
    │
    └──→ FEE_STRUCTURES (Pricing per Class/Term)
            │ id, school_id, category_id, class_id, 
            │ session_id, term_id, amount
            │
            └──→ Used to generate ──→ INVOICES (Bill for Student)
                                         │ id, school_id, student_id,
                                         │ invoice_number, session_id,
                                         │ term_id, total_amount,
                                         │ paid_amount, balance, status
                                         │
                                         ├──→ INVOICE_ITEMS (Many)
                                         │       breakdown of charges
                                         │
                                         └──→ PAYMENTS (Many)
                                                 │ id, school_id, invoice_id,
                                                 │ student_id, receipt_number,
                                                 │ amount, payment_method
                                                 │
                                                 └──→ PAYMENT_TRANSACTIONS
                                                         (Gateway details)

Example Flow:
    1. FEE_CATEGORY: "Tuition"
    2. FEE_STRUCTURE: JSS1 pays ₦50,000 per term
    3. INVOICE: Generated for Ada (JSS1A student)
    4. INVOICE_ITEMS: Tuition ₦50,000, Books ₦5,000
    5. PAYMENT: Ada pays ₦30,000 (partial)
    6. INVOICE: Balance ₦25,000


┌────────────────────────────────────────────────────────────────────────────┐
│                         EXPENSE MANAGEMENT                                  │
└────────────────────────────────────────────────────────────────────────────┘

EXPENSE_CATEGORIES
    │ id, school_id, name (e.g., Utilities, Salaries)
    │
    └──→ EXPENSES (Many)
            id, school_id, category_id, description,
            amount, expense_date, payment_method


┌────────────────────────────────────────────────────────────────────────────┐
│                         STAFF PAYROLL                                       │
└────────────────────────────────────────────────────────────────────────────┘

TEACHERS
    │ id, school_id, user_id, salary
    │
    └──→ TEACHER_PAYROLL (Many)
            id, school_id, teacher_id, month, year,
            basic_salary, allowances, deductions,
            net_salary, payment_date, status
```

---

## 📚 **LIBRARY MANAGEMENT**

```
BOOK_CATEGORIES
    │ id, school_id, name
    │
    └──→ LIBRARY_BOOKS (Many)
            │ id, school_id, category_id, title, author,
            │ isbn, quantity, available_quantity
            │
            └──→ BORROWED_BOOKS (Many)
                    id, school_id, book_id, student_id,
                    borrowed_date, due_date, return_date,
                    fine_amount, status

When book is borrowed:
    - LIBRARY_BOOKS.available_quantity decreases
    - BORROWED_BOOKS record created
When returned:
    - BORROWED_BOOKS.return_date updated
    - LIBRARY_BOOKS.available_quantity increases
```

---

## 🏨 **HOSTEL MANAGEMENT**

```
HOSTELS (Boarding Houses)
    │ id, school_id, name, hostel_type (boys/girls), capacity
    │
    └──→ HOSTEL_ROOMS (Many)
            │ id, school_id, hostel_id, room_number, 
            │ capacity, occupied
            │
            └──→ HOSTEL_ALLOCATIONS (Many)
                    id, school_id, student_id, room_id,
                    session_id, allocation_date, status
```

---

## 🚌 **TRANSPORT MANAGEMENT**

```
TRANSPORT_ROUTES
    │ id, school_id, route_name, fare
    │
    └──→ TRANSPORT_ASSIGNMENTS
            │ id, school_id, student_id, route_id, 
            │ vehicle_id, driver_id, pickup_point
            │
            ├──→ TRANSPORT_VEHICLES (via vehicle_id)
            │       id, school_id, vehicle_number, capacity
            │
            └──→ TRANSPORT_DRIVERS (via driver_id)
                    id, school_id, name, license_number
```

---

## 🎭 **EXTRACURRICULAR ACTIVITIES**

```
SCHOOL_HOUSES (House System)
    │ id, school_id, name, color, points
    │
    └──→ STUDENT_HOUSES (Many)
            id, school_id, student_id, house_id

CLUBS (School Clubs)
    │ id, school_id, name, coordinator_id (teacher)
    │
    └──→ CLUB_MEMBERS (Many)
            id, school_id, club_id, student_id, role

EVENTS (School Events)
    id, school_id, title, event_date, venue
```

---

## 📱 **COMMUNICATION SYSTEM**

```
NOTIFICATIONS
    id, school_id, user_id, title, message, is_read

ANNOUNCEMENTS
    id, school_id, title, body, target_audience, priority

MESSAGES (Internal Messaging)
    id, school_id, sender_id (user), receiver_id (user),
    subject, body, is_read

SMS_QUEUE → SMS_LOGS
    Queue SMS, then log when sent

EMAIL_QUEUE → EMAIL_LOGS
    Queue email, then log when sent
```

---

## 🎓 **ADMISSION WORKFLOW**

```
ADMISSION_APPLICATIONS (New Student Applications)
    │ id, school_id, application_number, first_name,
    │ class_applied_for, status
    │
    ├──→ ADMISSION_DOCUMENTS (Many)
    │       id, application_id, document_type, file_path
    │
    └──→ ADMISSION_OFFERS (1:1)
            id, school_id, application_id, offer_letter,
            offered_date, acceptance_deadline, status

When accepted → Student record created in STUDENTS table
```

---

## 🏗️ **FACILITIES & INFRASTRUCTURE**

```
CLASSROOM_ROOMS
    id, school_id, room_number, capacity, equipment

INVENTORY_CATEGORIES
    │ id, school_id, name
    │
    └──→ INVENTORY_ITEMS (Many)
            │ id, school_id, category_id, name, quantity
            │
            └──→ INVENTORY_TRANSACTIONS (Many)
                    track additions/removals

VISITORS (Gate Management)
    id, school_id, name, purpose, check_in_time, check_out_time
```

---

## ⚙️ **SYSTEM ADMINISTRATION**

```
SETTINGS (Key-Value Store)
    id, school_id, key, value, type

AUDIT_LOGS (Track all changes)
    id, school_id, user_id, action, model, model_id,
    old_values, new_values, ip_address

ACTIVITY_LOGS (User activities)
    id, school_id, user_id, description, subject_type,
    subject_id

DATABASE_BACKUPS (Your backup feature!)
    id, school_id, file_name, file_path, file_size, status

API_TOKENS (API Access)
    id, school_id, user_id, name, token, expires_at

LOGIN_SESSIONS (Track logins)
    id, school_id, user_id, ip_address, login_at, logout_at
```

---

## 📊 **COMPLETE RELATIONSHIP SUMMARY**

### **Core Foreign Keys:**

| Child Table | Foreign Key → Parent Table |
|-------------|---------------------------|
| All Tables | `school_id` → `SCHOOLS.id` |
| USERS | `role_id` → `ROLES.id` |
| TEACHERS | `user_id` → `USERS.id` |
| TEACHERS | `department_id` → `DEPARTMENTS.id` |
| GUARDIANS | `user_id` → `USERS.id` |
| CLASSES | `department_id` → `DEPARTMENTS.id` |
| CLASS_ARMS | `class_id` → `CLASSES.id` |
| CLASS_ARMS | `teacher_id` → `TEACHERS.id` |
| STUDENTS | `class_id` → `CLASSES.id` |
| STUDENTS | `arm_id` → `CLASS_ARMS.id` |
| SUBJECTS | `department_id` → `DEPARTMENTS.id` |
| ACADEMIC_TERMS | `session_id` → `ACADEMIC_SESSIONS.id` |

### **Junction Tables (Many-to-Many):**

| Junction Table | Links | Purpose |
|----------------|-------|---------|
| ROLE_PERMISSIONS | ROLES ↔ PERMISSIONS | Role access control |
| TEACHER_SUBJECTS | TEACHERS ↔ SUBJECTS ↔ CLASSES | Teaching assignments |
| GUARDIAN_STUDENTS | GUARDIANS ↔ STUDENTS | Parent-child relationships |
| CLUB_MEMBERS | CLUBS ↔ STUDENTS | Club participation |
| STUDENT_HOUSES | SCHOOL_HOUSES ↔ STUDENTS | House allocation |

---

## 🔒 **DATA ISOLATION MECHANISM**

```
Every query MUST filter by school_id:

Example Laravel Query:
    Student::where('school_id', auth()->user()->school_id)
           ->where('class_id', $classId)
           ->get();

This ensures:
    ✅ School A cannot see School B's data
    ✅ Faster queries (indexed on school_id)
    ✅ Automatic multi-tenancy
```

---

## 📈 **RELATIONSHIP COUNTS**

- **1:1 Relationships**: 2 (Users↔Teachers, Users↔Guardians)
- **1:Many Relationships**: 200+ (Most tables)
- **Many:Many Relationships**: 5 (Via junction tables)
- **Total Foreign Keys**: 300+ across 98 tables

---

**Generated**: 2026-07-13  
**Purpose**: Complete relationship mapping for development & debugging  
**Version**: 1.0
