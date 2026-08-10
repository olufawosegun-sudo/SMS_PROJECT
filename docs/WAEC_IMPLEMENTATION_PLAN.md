# WAEC Payment & Candidate Management - Implementation Plan

## Project Analysis Summary

### Existing Architecture
- **Framework**: Laravel 12.63.0, PHP 8.3, MySQL
- **Pattern**: Repository Pattern with Service Layer
- **Authorization**: Policy-based (Controller->authorize())
- **UI**: Blade templates with Tailwind CSS v4
- **Payment System**: Existing Payment/Invoice models with basic workflow

### Key Findings
1. **Payment Architecture EXISTS**: `payments`, `invoices`, `payment_transactions` tables
2. **Repository Pattern**: BaseRepository, PaymentRepository, StudentRepository
3. **Service Layer**: PaymentService, StudentService, TeacherService
4. **Roles Present**: Owner, Principal, Vice Principal, Teacher, Guardian, Student, Accountant
5. **NO Bursar Role**: Confirmed - Principal will be approval authority
6. **Fee System**: FeeCategory model for configurable fees
7. **Notification**: Notification model exists but not actively used yet
8. **Academic Context**: AcademicSession and AcademicTerm models available
9. **Authorization**: StudentPolicy pattern - need WaecCandidatePolicy and WaecPaymentPolicy

### Integration Points
- Reuse existing Payment model and infrastructure
- Extend PaymentService for WAEC-specific logic
- Follow StudentRepository pattern for WaecCandidateRepository
- Integrate into existing dashboard views (guardian.blade.php, student.blade.php, principal.blade.php, owner.blade.php)
- Follow existing authorization pattern (Policy + $this->authorize())

---

## Implementation Plan

### Phase 3: Database Layer

#### New Tables Required

1. **waec_candidates** (tracks students registered for WAEC)
   - Links: students, academic_sessions, classes
   - Prevents duplicate registration per session
   - Tracks payment status and eligibility

2. **waec_payments** (WAEC-specific payment records)
   - Links to: waec_candidates, students, guardians, payments (generic)
   - Approval workflow: pending → submitted → approved/rejected
   - Audit trail: submitted_by, approved_by, rejected_by

3. **waec_payment_approvals** (approval audit trail)
   - Tracks all approval/rejection actions
   - Records Principal decisions with timestamps
   - Maintains complete history

4. **waec_fee_configurations** (configurable WAEC fees)
   - Per academic session
   - Owner can set/update amounts
   - Types: examination_fee, registration_fee, other

#### Models to Create
- `WaecCandidate` (with relationships)
- `WaecPayment` (with approval workflow)
- `WaecPaymentApproval` (audit model)
- `WaecFeeConfiguration` (fee settings)

#### Repositories to Create
- `WaecCandidateRepository extends BaseRepository`
- `WaecPaymentRepository extends BaseRepository`

---

### Phase 4: Backend Logic

#### Services
1. **WaecCandidateService**
   - `registerCandidate()` - Principal adds student as candidate
   - `getCandidatesBySession()` - Filter candidates
   - `getCandidatePaymentStatus()` - Check payment completion
   - `getCandidateStatistics()` - For dashboards
   - `removeCand candidate()` - If permitted

2. **WaecPaymentService**
   - `submitPayment()` - Guardian/Student submits
   - `approvePayment()` - Principal approves
   - `rejectPayment()` - Principal rejects (requires reason)
   - `getPendingPayments()` - For Principal dashboard
   - `getPaymentHistory()` - For all roles
   - `generatePaymentReference()` - Unique reference
   - `calculateOutstandingBalance()` - Student balance
   - `getPaymentStatistics()` - For Owner reports

3. **WaecReportService** (Owner analytics)
   - `getFinancialSummary()`
   - `getPaymentsBySession()`
   - `getPaymentsByClass()`
   - `getCollectionRate()`
   - `exportPaymentReport()` - PDF/CSV

#### Policies
1. **WaecCandidatePolicy**
   - `viewAny()` - Owner, Principal, Teacher (view only)
   - `view()` - Owner, Principal, own guardian/student
   - `create()` - Owner, Principal
   - `delete()` - Owner, Principal (with conditions)

2. **WaecPaymentPolicy**
   - `viewAny()` - Owner, Principal
   - `view()` - Owner, Principal, own guardian/student
   - `create()` - Guardian, Student (own only)
   - `approve()` - Principal ONLY
   - `reject()` - Principal ONLY
   - `viewReports()` - Owner ONLY

#### Controllers
1. **WaecCandidateController** (Principal & Owner)
   - `index()` - List candidates
   - `store()` - Register candidate
   - `show()` - Candidate details
   - `destroy()` - Remove candidate

2. **WaecPaymentController** (Principal & Owner)
   - `index()` - List all payments
   - `show()` - Payment details
   - `approve()` - Approve payment
   - `reject()` - Reject payment

3. **Guardian/StudentWaecPaymentController**
   - `index()` - View own candidates & payments
   - `store()` - Submit payment
   - `show()` - View payment status
   - `receipt()` - Download receipt

4. **OwnerWaecReportController**
   - `index()` - Reports dashboard
   - `financial()` - Financial summary
   - `export()` - PDF/CSV export

---

### Phase 5: Routes

```php
// routes/web.php - Protected routes middleware('auth')

// Guardian/Student WAEC Routes
Route::prefix('waec')->name('waec.')->group(function () {
    Route::get('/candidates', [GuardianWaecController::class, 'candidates'])->name('candidates');
    Route::get('/payments', [GuardianWaecController::class, 'payments'])->name('payments');
    Route::post('/payments', [GuardianWaecController::class, 'submitPayment'])->name('payments.submit');
    Route::get('/payments/{payment}', [GuardianWaecController::class, 'showPayment'])->name('payments.show');
    Route::get('/payments/{payment}/receipt', [GuardianWaecController::class, 'receipt'])->name('payments.receipt');
});

// Principal WAEC Routes
Route::prefix('principal/waec')->name('principal.waec.')->group(function () {
    Route::get('/candidates', [PrincipalWaecController::class, 'candidates'])->name('candidates');
    Route::post('/candidates', [PrincipalWaecController::class, 'storeCandidate'])->name('candidates.store');
    Route::delete('/candidates/{candidate}', [PrincipalWaecController::class, 'destroyCandidate'])->name('candidates.destroy');
    Route::get('/payments', [PrincipalWaecController::class, 'payments'])->name('payments');
    Route::get('/payments/pending', [PrincipalWaecController::class, 'pendingPayments'])->name('payments.pending');
    Route::post('/payments/{payment}/approve', [PrincipalWaecController::class, 'approvePayment'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PrincipalWaecController::class, 'rejectPayment'])->name('payments.reject');
});

// Owner WAEC Routes
Route::prefix('owner/waec')->name('owner.waec.')->group(function () {
    Route::get('/reports', [OwnerWaecController::class, 'reports'])->name('reports');
    Route::get('/reports/financial', [OwnerWaecController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/export', [OwnerWaecController::class, 'export'])->name('reports.export');
    Route::get('/fees/configuration', [OwnerWaecController::class, 'feeConfiguration'])->name('fees.configuration');
    Route::post('/fees/configuration', [OwnerWaecController::class, 'updateFeeConfiguration'])->name('fees.update');
});
```

---

### Phase 6: Guardian/Student Interface

#### Dashboard Integration (guardian.blade.php / student.blade.php)
- Add "WAEC Payments" card in main dashboard
- Show eligible candidates
- Display payment status badges
- Link to full WAEC section

#### New Views Required
1. **resources/views/waec/guardian/candidates.blade.php**
   - List of student's WAEC candidacy
   - Payment status per candidate
   - Outstanding balance
   - Payment history

2. **resources/views/waec/guardian/payments/index.blade.php**
   - All WAEC payments
   - Filter by candidate/session
   - Status indicators

3. **resources/views/waec/guardian/payments/create.blade.php**
   - Payment submission form
   - Amount display
   - Payment method selection
   - Upload proof (if required)
   - Reference number entry

4. **resources/views/waec/guardian/payments/show.blade.php**
   - Payment details
   - Status badge
   - Approval/rejection information
   - Receipt download (if approved)

---

### Phase 7: Principal Dashboard Integration

#### Dashboard Integration (principal.blade.php)
- Add "WAEC Management" section
- Pending payments count badge
- Quick stats: candidates, payments, approvals needed

#### New Views Required
1. **resources/views/waec/principal/candidates/index.blade.php**
   - All WAEC candidates
   - Search/filter by class, session
   - Add new candidate button
   - Payment status column
   - Actions: view, remove

2. **resources/views/waec/principal/candidates/create.blade.php**
   - Select student
   - Select session
   - Assign WAEC fee
   - Confirm registration

3. **resources/views/waec/principal/payments/index.blade.php**
   - All WAEC payments
   - Tabs: All, Pending, Approved, Rejected
   - Filter by date, class, session
   - Bulk actions consideration

4. **resources/views/waec/principal/payments/pending.blade.php**
   - Pending payments queue
   - Priority sorting
   - Quick approve/reject actions

5. **resources/views/waec/principal/payments/show.blade.php**
   - Full payment details
   - Student/guardian information
   - Payment proof viewer
   - Approval form with reason
   - Rejection form with reason field
   - Approval history

---

### Phase 8: Owner Dashboard Integration

#### Dashboard Integration (owner.blade.php)
- Add "WAEC Financial Overview" section
- Summary cards: Total candidates, Total expected, Total collected, Outstanding
- Chart: Payment collection trend
- Quick link to full reports

#### New Views Required
1. **resources/views/waec/owner/reports/index.blade.php**
   - Financial summary dashboard
   - Key metrics cards
   - Charts: collection rate, payments by class
   - Recent activity feed

2. **resources/views/waec/owner/reports/financial.blade.php**
   - Detailed financial breakdown
   - By session
   - By class/arm
   - By payment method
   - Payment status distribution

3. **resources/views/waec/owner/reports/export.blade.php**
   - Export options (PDF, Excel, CSV)
   - Date range selector
   - Filter options

4. **resources/views/waec/owner/fees/configuration.blade.php**
   - Configure WAEC fees per session
   - Set examination fee
   - Set registration fee
   - Other charges
   - Update history

---

### Phase 9: Receipts and Notifications

#### Receipt Generation
- **WaecReceiptService**
  - `generateReceipt()` - Create PDF receipt
  - Use existing receipt layout pattern if exists
  - Include: school branding, student details, payment details, approval info

#### Notification Integration
- Use existing Notification model
- Trigger points:
  1. Payment submitted → Notify Principal
  2. Payment approved → Notify Guardian/Student
  3. Payment rejected → Notify Guardian/Student (with reason)
  4. New candidate registered → Notify Guardian/Student

#### Email Integration (if email system exists)
- Welcome email when registered as candidate
- Payment confirmation email
- Approval notification email
- Rejection notification email with reason

---

### Phase 10: Testing & Validation

#### Database Testing
- [ ] Run migrations successfully
- [ ] Test foreign key constraints
- [ ] Test unique constraints (no duplicate candidates per session)
- [ ] Test cascading deletes

#### Authorization Testing
- [ ] Guardian can only view/pay for own wards
- [ ] Student can only view own payments
- [ ] Teacher cannot approve payments
- [ ] Principal can approve/reject payments
- [ ] Owner can view all reports
- [ ] Unauthorized roles blocked at controller level

#### Workflow Testing
1. **Happy Path**
   - [ ] Principal registers candidate
   - [ ] Guardian views candidate & fee
   - [ ] Guardian submits payment
   - [ ] Payment appears as pending
   - [ ] Principal approves payment
   - [ ] Status updates to approved
   - [ ] Guardian can download receipt
   - [ ] Owner sees payment in reports

2. **Rejection Path**
   - [ ] Guardian submits payment
   - [ ] Principal rejects with reason
   - [ ] Guardian sees rejection reason
   - [ ] Guardian can resubmit

3. **Edge Cases**
   - [ ] Duplicate candidate registration blocked
   - [ ] Duplicate payment reference blocked
   - [ ] Cannot approve already approved payment
   - [ ] Rejection reason required
   - [ ] Payment amount matches configuration

#### UI Testing
- [ ] Mobile responsive
- [ ] Tables paginate correctly
- [ ] Search/filters work
- [ ] Status badges display correctly
- [ ] Forms validate properly
- [ ] Success/error messages show
- [ ] Confirmation dialogs prevent accidental actions

#### Integration Testing
- [ ] No existing features broken
- [ ] Dashboard navigation works
- [ ] Authorization consistent
- [ ] Database relationships intact
- [ ] Existing payments unaffected

---

## Technical Specifications

### Payment Status Flow
```
PENDING → SUBMITTED → APPROVED
                   ↘ REJECTED
```

### Approval Workflow
1. Guardian/Student submits payment (`status: SUBMITTED`)
2. Principal reviews (`WaecPaymentController@show`)
3. Principal approves OR rejects
4. If approved: Create `WaecPaymentApproval` record, update status
5. If rejected: Store rejection reason, notify submitter
6. Create audit log entry

### Security Measures
- Policy authorization on all routes
- CSRF protection (Laravel default)
- File upload validation (payment proof)
- SQL injection protection (Eloquent ORM)
- Mass assignment protection ($fillable)
- XSS protection (Blade {{ }} escaping)
- Rate limiting on payment submission
- Transaction locking on approval

### Performance Considerations
- Eager load relationships to avoid N+1
- Index foreign keys
- Cache fee configurations
- Paginate large result sets
- Optimize dashboard queries

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── GuardianWaecController.php
│   │   ├── PrincipalWaecController.php
│   │   └── OwnerWaecController.php
│   ├── Requests/
│   │   ├── StoreWaecCandidateRequest.php
│   │   ├── SubmitWaecPaymentRequest.php
│   │   ├── ApproveWaecPaymentRequest.php
│   │   └── RejectWaecPaymentRequest.php
│   └── Resources/
│       ├── WaecCandidateResource.php
│       └── WaecPaymentResource.php
├── Models/
│   ├── WaecCandidate.php
│   ├── WaecPayment.php
│   ├── WaecPaymentApproval.php
│   └── WaecFeeConfiguration.php
├── Policies/
│   ├── WaecCandidatePolicy.php
│   └── WaecPaymentPolicy.php
├── Repositories/
│   ├── WaecCandidateRepository.php
│   └── WaecPaymentRepository.php
└── Services/
    ├── WaecCandidateService.php
    ├── WaecPaymentService.php
    └── WaecReceiptService.php

database/
└── migrations/
    ├── xxxx_create_waec_fee_configurations_table.php
    ├── xxxx_create_waec_candidates_table.php
    ├── xxxx_create_waec_payments_table.php
    └── xxxx_create_waec_payment_approvals_table.php

resources/
└── views/
    └── waec/
        ├── guardian/
        │   ├── candidates.blade.php
        │   └── payments/
        │       ├── index.blade.php
        │       ├── create.blade.php
        │       └── show.blade.php
        ├── principal/
        │   ├── candidates/
        │   │   ├── index.blade.php
        │   │   └── create.blade.php
        │   └── payments/
        │       ├── index.blade.php
        │       ├── pending.blade.php
        │       └── show.blade.php
        ├── owner/
        │   ├── reports/
        │   │   ├── index.blade.php
        │   │   ├── financial.blade.php
        │   │   └── export.blade.php
        │   └── fees/
        │       └── configuration.blade.php
        └── components/
            ├── payment-status-badge.blade.php
            ├── candidate-card.blade.php
            └── payment-summary-card.blade.php
```

---

## Success Criteria

✅ Principal can register WAEC candidates
✅ Guardian/Student can view candidates and fees
✅ Guardian/Student can submit WAEC payments
✅ Principal can approve/reject payments with audit trail
✅ Owner can view comprehensive financial reports
✅ All authorization properly enforced
✅ No existing features broken
✅ Mobile responsive UI
✅ Complete audit trail maintained
✅ Receipts generated for approved payments
✅ Notifications sent at key points
✅ All tests passing

---

## Risks & Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| Breaking existing payment system | HIGH | Careful integration, thorough testing |
| Authorization bypass | HIGH | Policy enforcement at controller level, backend validation |
| Duplicate payments | MEDIUM | Unique constraints, transaction locking |
| UI inconsistency | LOW | Follow existing component patterns |
| Performance issues | MEDIUM | Eager loading, indexing, caching |
| Mobile UI breaking | MEDIUM | Responsive testing throughout |

---

## Next Steps
1. Create database migrations ✓ (Phase 3)
2. Create models with relationships ✓ (Phase 3)
3. Create repositories ✓ (Phase 3)
4. Create services ✓ (Phase 4)
5. Create policies ✓ (Phase 4)
6. Create controllers ✓ (Phase 4)
7. Add routes ✓ (Phase 5)
8. Build Guardian/Student views ✓ (Phase 6)
9. Build Principal views ✓ (Phase 7)
10. Build Owner views ✓ (Phase 8)
11. Implement receipts & notifications ✓ (Phase 9)
12. Test complete system ✓ (Phase 10)
