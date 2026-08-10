---
name: sms-payment-processing
description: Process payments, generate invoices, and manage financial transactions in the SMS system
---

# SMS Payment Processing

## When to use this skill
Use this skill when working with fee payments, invoice generation, payment confirmations, or financial reporting.

## Architecture Pattern

### Always follow:
Controller → PaymentService → PaymentRepository → Model

## Key Components

**Repository**: `app/Repositories/PaymentRepository.php`
**Service**: `app/Services/PaymentService.php`
**Controller**: `app/Http/Controllers/PaymentController.php`

## Recording a Payment

```php
// In controller
public function store(Request $request)
{
    $payment = $this->paymentService->recordPayment(
        $request->validated(),
        Auth::user()->school->id
    );
    return redirect()->route('payments.index');
}

// Service method
public function recordPayment(array $data, int $schoolId)
{
    return DB::transaction(function () use ($data, $schoolId) {
        $reference = $this->generatePaymentReference();
        $payment = $this->paymentRepository->create([
            'school_id' => $schoolId,
            'student_id' => $data['student_id'],
            'amount' => $data['amount'],
            'reference' => $reference,
            'status' => 'pending',
        ]);
        return $payment;
    });
}
```

## Key Rules

1. ✅ ALWAYS generate unique payment references
2. ✅ ALWAYS use transactions for payment operations
3. ✅ ALWAYS verify school ownership
4. ✅ ALWAYS log payment activities
5. ✅ ALWAYS validate payment amounts
