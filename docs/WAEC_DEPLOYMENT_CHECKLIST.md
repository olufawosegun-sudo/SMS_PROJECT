# WAEC Module - Deployment Checklist

**Project:** Secondary School Management System  
**Module:** WAEC Payment & Candidate Management  
**Date:** August 9, 2026  
**Status:** ✅ Ready for Deployment

---

## Pre-Deployment Verification ✅

### Database Layer
- [x] 4 migrations created and tested
  - `2026_08_09_223355_create_waec_fee_configurations_table.php`
  - `2026_08_09_223356_create_waec_candidates_table.php`
  - `2026_08_09_223344_create_waec_payments_table.php`
  - `2026_08_09_223344_create_waec_payment_approvals_table.php`
- [x] All migrations successfully run (Batch 17-20)
- [x] Duplicate migration files removed
- [x] Foreign key relationships verified
- [x] Indexes created for performance

### Backend Components
- [x] 4 Models created with relationships
- [x] 2 Repositories implemented
- [x] 3 Services with business logic
- [x] 2 Policies for authorization
- [x] 3 Controllers (Guardian, Principal, Owner)
- [x] 4 Form Request classes with validation
- [x] 23 Routes registered and verified

### Frontend Views
- [x] 4 Guardian views created
- [x] 4 Principal views created
- [x] 2 Owner views created
- [x] 1 Receipt template created
- [x] All views responsive and styled

### Documentation
- [x] Implementation Plan (`WAEC_IMPLEMENTATION_PLAN.md`)
- [x] Implementation Summary (`WAEC_IMPLEMENTATION_SUMMARY.md`)
- [x] Deployment Checklist (this document)
- [x] Project patterns documented

### Code Quality
- [x] All code formatted with Laravel Pint
- [x] Follows existing SMS architecture patterns
- [x] Repository-Service-Controller pattern maintained
- [x] Policy-based authorization implemented
- [x] No hardcoded values or magic numbers

---

## Deployment Steps

### Step 1: Backup Current System
```bash
# Backup database
php artisan db:backup

# Backup files
tar -czf backup-$(date +%Y%m%d).tar.gz app/ database/ resources/ routes/ config/
```

### Step 2: Deploy Code
```bash
# Pull latest code
git pull origin main

# Or upload files via FTP/SFTP
# Upload all modified files listed below
```

### Step 3: Run Migrations
```bash
# Check migration status
php artisan migrate:status

# Run pending migrations
php artisan migrate --force

# Verify WAEC tables created
php artisan db:show
```

### Step 4: Clear Application Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Step 5: Rebuild Optimized Files
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 6: Set File Permissions
```bash
# Create storage directory for WAEC payments
mkdir -p storage/app/public/waec-payments
chmod -R 775 storage/app/public/waec-payments

# Ensure storage link exists
php artisan storage:link

# Set ownership (adjust user as needed)
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### Step 7: Verify Routes
```bash
# Check WAEC routes are registered
php artisan route:list --name=waec

# Should show 23 routes
```

---

## Post-Deployment Testing

### Manual Testing Checklist

#### A. Guardian/Student Testing
1. [ ] **Login** as Guardian user
2. [ ] **Navigate** to WAEC section from dashboard
3. [ ] **View** WAEC candidates list
4. [ ] **Verify** payment status shows correctly (unpaid/partially paid/fully paid)
5. [ ] **Click** "Make Payment" button
6. [ ] **Fill** payment form with test data
7. [ ] **Upload** a test proof document (PDF or image)
8. [ ] **Submit** payment
9. [ ] **Verify** payment shows as "Submitted" status
10. [ ] **View** payment details page
11. [ ] **Check** payment proof document is accessible
12. [ ] **Try** downloading receipt (should fail - not yet approved)
13. [ ] **Navigate** to payments list
14. [ ] **Verify** submitted payment appears in list

#### B. Principal Testing
15. [ ] **Login** as Principal user
16. [ ] **Navigate** to WAEC Candidates section
17. [ ] **Click** "Register Candidate"
18. [ ] **Select** a student from dropdown
19. [ ] **Choose** session and class
20. [ ] **Register** candidate successfully
21. [ ] **Verify** candidate appears in candidates list
22. [ ] **Check** total fee is auto-calculated
23. [ ] **Navigate** to Pending Payments
24. [ ] **Verify** guardian's submitted payment appears
25. [ ] **Click** "View Details" on payment
26. [ ] **Review** payment information
27. [ ] **View** uploaded proof document
28. [ ] **Click** "Approve" button
29. [ ] **Add** optional comment
30. [ ] **Submit** approval
31. [ ] **Verify** receipt number is generated
32. [ ] **Check** payment status changed to "Approved"
33. [ ] **Verify** candidate balance updated correctly
34. [ ] **Test** rejecting a payment (optional)
35. [ ] **Verify** rejection reason is required
36. [ ] **Check** guardian is notified of rejection

#### C. Owner Testing
37. [ ] **Login** as Owner user
38. [ ] **Navigate** to WAEC Reports
39. [ ] **View** financial summary cards
40. [ ] **Check** total collected amount is correct
41. [ ] **Review** payments by session chart
42. [ ] **Review** payments by class chart
43. [ ] **Check** payment methods distribution
44. [ ] **View** recent activities feed
45. [ ] **Navigate** to Fee Configuration
46. [ ] **Select** a session
47. [ ] **Set** examination fee amount
48. [ ] **Set** registration fee amount
49. [ ] **Save** configuration
50. [ ] **Load** existing configuration
51. [ ] **Verify** fees are editable
52. [ ] **Test** export data functionality
53. [ ] **Download** CSV file
54. [ ] **Verify** CSV contains correct data

#### D. Cross-Role Testing
55. [ ] **Verify** Guardian cannot access Principal routes
56. [ ] **Verify** Guardian cannot access Owner routes
57. [ ] **Verify** Principal cannot access Owner fee config
58. [ ] **Verify** Student can view their own candidate data
59. [ ] **Verify** Guardian can only see their wards' data

#### E. Receipt Testing
60. [ ] **Login** as Guardian
61. [ ] **Navigate** to approved payment
62. [ ] **Click** "Download Receipt"
63. [ ] **Verify** receipt opens in new tab
64. [ ] **Check** school information is correct
65. [ ] **Check** student information is correct
66. [ ] **Check** payment amount is correct
67. [ ] **Check** receipt number is displayed
68. [ ] **Check** approval information is shown
69. [ ] **Click** print button
70. [ ] **Verify** receipt prints correctly

---

## Database Verification Queries

### Check Tables Created
```sql
SHOW TABLES LIKE 'waec_%';
-- Should return 4 tables:
-- waec_candidates
-- waec_fee_configurations
-- waec_payment_approvals
-- waec_payments
```

### Verify Foreign Keys
```sql
SELECT 
    TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, 
    REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'your_database_name'
AND TABLE_NAME LIKE 'waec_%'
AND REFERENCED_TABLE_NAME IS NOT NULL;
```

### Check Indexes
```sql
SHOW INDEXES FROM waec_candidates;
SHOW INDEXES FROM waec_payments;
SHOW INDEXES FROM waec_fee_configurations;
SHOW INDEXES FROM waec_payment_approvals;
```

### Verify Data Integrity
```sql
-- Check candidate-payment relationship
SELECT 
    COUNT(DISTINCT c.id) as candidates,
    COUNT(p.id) as payments
FROM waec_candidates c
LEFT JOIN waec_payments p ON c.id = p.candidate_id;

-- Check payment status distribution
SELECT status, COUNT(*) as count
FROM waec_payments
GROUP BY status;

-- Check approval trail completeness
SELECT 
    p.id, p.payment_reference, p.status,
    COUNT(a.id) as approval_count
FROM waec_payments p
LEFT JOIN waec_payment_approvals a ON p.id = a.payment_id
GROUP BY p.id;
```

---

## Performance Checks

### Response Time Testing
```bash
# Test Guardian candidates page
curl -w "@curl-format.txt" -o /dev/null -s "https://your-domain.com/waec/candidates"

# Test Principal payments page
curl -w "@curl-format.txt" -o /dev/null -s "https://your-domain.com/principal/waec/payments"

# Test Owner reports page
curl -w "@curl-format.txt" -o /dev/null -s "https://your-domain.com/owner/waec/reports"

# Target: < 200ms for most pages
```

### Database Query Performance
```sql
-- Check for slow queries
EXPLAIN SELECT * FROM waec_candidates 
WHERE school_id = 1 AND session_id = 1;

EXPLAIN SELECT * FROM waec_payments 
WHERE status = 'submitted' AND school_id = 1;

-- Should use indexes for both queries
```

---

## Security Verification

### Authorization Testing
1. [ ] Test Guardian cannot access `/principal/waec/*` routes
2. [ ] Test Guardian cannot access `/owner/waec/*` routes
3. [ ] Test Principal cannot access `/owner/waec/*` routes
4. [ ] Test unauthorized users get 403 errors
5. [ ] Test school data isolation (user from school A cannot see school B data)

### File Upload Security
1. [ ] Test uploading invalid file types (should reject)
2. [ ] Test uploading oversized files (> 2MB should reject)
3. [ ] Test accessing other users' uploaded files (should fail)
4. [ ] Verify files are stored in non-public directory

### SQL Injection Testing
1. [ ] Test search fields with SQL injection patterns
2. [ ] Test filter parameters with malicious input
3. [ ] Verify all queries use parameter binding

---

## Rollback Plan

### If Issues Occur Post-Deployment

#### Option 1: Database Rollback
```bash
# Rollback WAEC migrations only
php artisan migrate:rollback --step=4

# This will remove:
# - waec_payment_approvals
# - waec_payments
# - waec_candidates
# - waec_fee_configurations
```

#### Option 2: Full System Rollback
```bash
# Restore database from backup
mysql -u username -p database_name < backup-YYYYMMDD.sql

# Restore code from backup
tar -xzf backup-YYYYMMDD.tar.gz

# Clear caches
php artisan optimize:clear
```

#### Option 3: Disable WAEC Module
```php
// In routes/web.php
// Comment out WAEC route groups (lines ~278-325)

// Clear route cache
php artisan route:clear
```

---

## Monitoring & Maintenance

### First 24 Hours
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Check payment submissions count
- [ ] Verify receipt generation working
- [ ] Monitor server resources (CPU/Memory)
- [ ] Review user feedback

### First Week
- [ ] Review all submitted payments
- [ ] Check for any authorization issues
- [ ] Monitor file storage usage
- [ ] Review notification delivery
- [ ] Check database performance

### Ongoing
- [ ] Weekly: Review payment approval workflow
- [ ] Monthly: Archive completed sessions data
- [ ] Quarterly: Audit payment records
- [ ] Annual: Review and optimize database indexes

---

## Common Issues & Solutions

### Issue: Migrations Fail
**Symptom:** Migration errors during deployment  
**Solution:**
```bash
# Check migration status
php artisan migrate:status

# If specific migration fails, check for:
# - Duplicate column names
# - Foreign key constraints
# - Missing parent tables

# Fix migration file and retry
php artisan migrate --force
```

### Issue: Routes Not Found (404)
**Symptom:** WAEC pages return 404  
**Solution:**
```bash
# Clear and rebuild route cache
php artisan route:clear
php artisan route:cache

# Verify routes are registered
php artisan route:list --name=waec
```

### Issue: Authorization Errors (403)
**Symptom:** Users get "Unauthorized" errors  
**Solution:**
- Verify user roles are correct (Guardian, Principal, Owner)
- Check policies are registered in `AuthServiceProvider`
- Ensure school_id matches between user and data

### Issue: File Upload Fails
**Symptom:** Payment proof documents won't upload  
**Solution:**
```bash
# Check storage permissions
chmod -R 775 storage/app/public/waec-payments

# Verify storage link exists
php artisan storage:link

# Check disk space
df -h

# Increase PHP upload limits in php.ini:
# upload_max_filesize = 2M
# post_max_size = 2M
```

### Issue: Receipt Not Generating
**Symptom:** Receipt link doesn't work after approval  
**Solution:**
- Verify payment status is "approved"
- Check receipt_number is set in database
- Ensure receipt view exists: `resources/views/waec/receipts/payment.blade.php`
- Check for PHP errors in logs

---

## Success Criteria

### The deployment is successful when:

✅ All 4 WAEC database tables exist and have correct structure  
✅ All 23 routes are accessible  
✅ Guardian can submit payments successfully  
✅ Principal can approve/reject payments  
✅ Owner can view reports and configure fees  
✅ Receipt generation works for approved payments  
✅ File uploads work and are secure  
✅ Authorization prevents unauthorized access  
✅ School data isolation is maintained  
✅ No errors in application logs  
✅ Performance is acceptable (< 200ms response times)  
✅ All test scenarios pass

---

## Support Contacts

**Technical Issues:**  
- Check documentation: `docs/WAEC_IMPLEMENTATION_SUMMARY.md`
- Review logs: `storage/logs/laravel.log`
- Database queries: Use provided verification queries above

**User Training:**  
- Guardian Guide: Section 4.1 in Implementation Summary
- Principal Guide: Section 4.2 in Implementation Summary  
- Owner Guide: Section 4.3 in Implementation Summary

---

## Sign-Off

### Deployment Completed By:
- **Name:** _________________
- **Date:** _________________
- **Signature:** _________________

### Deployment Verified By:
- **Name:** _________________
- **Date:** _________________
- **Signature:** _________________

### Issues Encountered:
```
[List any issues encountered during deployment and how they were resolved]
```

### Notes:
```
[Any additional notes about the deployment]
```

---

**DEPLOYMENT STATUS:** ✅ READY FOR PRODUCTION

*End of Deployment Checklist*
