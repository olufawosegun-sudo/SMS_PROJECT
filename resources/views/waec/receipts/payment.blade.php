<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WAEC Payment Receipt - {{ $payment->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; padding: 40px; background: #f5f5f5; }
        .receipt { max-width: 800px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .school-name { font-size: 24px; font-weight: bold; color: #1e293b; margin-bottom: 5px; }
        .school-info { color: #64748b; font-size: 14px; line-height: 1.6; }
        .receipt-title { text-align: center; font-size: 28px; font-weight: bold; color: #2563eb; margin: 20px 0; }
        .receipt-number { text-align: center; font-size: 16px; color: #64748b; margin-bottom: 30px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: bold; color: #1e293b; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .info-item { margin-bottom: 10px; }
        .info-label { font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 14px; color: #1e293b; font-weight: 600; margin-top: 4px; }
        .amount-section { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 30px 0; }
        .amount-label { font-size: 14px; color: #64748b; margin-bottom: 8px; }
        .amount-value { font-size: 36px; font-weight: bold; color: #10b981; }
        .status-badge { display: inline-block; padding: 8px 16px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 14px; font-weight: 600; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; text-align: center; }
        .signature-section { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 2px solid #1e293b; padding-top: 8px; margin-top: 60px; }
        .print-button { background: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
        .print-button:hover { background: #1d4ed8; }
        @media print {
            body { padding: 0; background: white; }
            .print-button { display: none; }
            .receipt { box-shadow: none; }
        }
    </style>
</head>
<body>
    @php
        $currencySymbol = $payment->school->currency_symbol ?? '₦';
    @endphp

    <div class="receipt">
        {{-- Header --}}
        <div class="header">
            <div class="school-name">{{ $payment->school->name }}</div>
            <div class="school-info">
                @if($payment->school->address)
                    {{ $payment->school->address }}<br>
                @endif
                @if($payment->school->phone)
                    Phone: {{ $payment->school->phone }} | 
                @endif
                @if($payment->school->email)
                    Email: {{ $payment->school->email }}
                @endif
            </div>
        </div>

        {{-- Receipt Title --}}
        <div class="receipt-title">WAEC PAYMENT RECEIPT</div>
        <div class="receipt-number">
            Receipt No: <strong>{{ $payment->receipt_number }}</strong> | 
            Payment Ref: <strong>{{ $payment->payment_reference }}</strong>
        </div>

        {{-- Status --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <span class="status-badge">✓ APPROVED & VERIFIED</span>
        </div>

        {{-- Student Information --}}
        <div class="section">
            <div class="section-title">Student Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Student Name</div>
                    <div class="info-value">{{ $payment->candidate->student->user->first_name }} {{ $payment->candidate->student->user->last_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Admission Number</div>
                    <div class="info-value">{{ $payment->candidate->student->admission_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Class</div>
                    <div class="info-value">{{ $payment->candidate->schoolClass->name }}{{ $payment->candidate->arm ? ' ' . $payment->candidate->arm->name : '' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Academic Session</div>
                    <div class="info-value">{{ $payment->candidate->session->name }}</div>
                </div>
                @if($payment->candidate->candidate_number)
                <div class="info-item">
                    <div class="info-label">WAEC Candidate Number</div>
                    <div class="info-value">{{ $payment->candidate->candidate_number }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment Amount --}}
        <div class="amount-section">
            <div class="amount-label">Amount Paid</div>
            <div class="amount-value">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</div>
        </div>

        {{-- Payment Details --}}
        <div class="section">
            <div class="section-title">Payment Details</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Payment Date</div>
                    <div class="info-value">{{ $payment->payment_date->format('F d, Y') }}</div>
                </div>
                @if($payment->bank_name)
                <div class="info-item">
                    <div class="info-label">Bank Name</div>
                    <div class="info-value">{{ $payment->bank_name }}</div>
                </div>
                @endif
                @if($payment->transaction_reference)
                <div class="info-item">
                    <div class="info-label">Transaction Reference</div>
                    <div class="info-value">{{ $payment->transaction_reference }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Approval Details --}}
        <div class="section">
            <div class="section-title">Approval Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Approved By</div>
                    <div class="info-value">{{ $payment->approver->first_name ?? 'N/A' }} {{ $payment->approver->last_name ?? '' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Approval Date</div>
                    <div class="info-value">{{ $payment->approved_at ? $payment->approved_at->format('F d, Y h:i A') : 'N/A' }}</div>
                </div>
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="section">
            <div class="section-title">Payment Summary</div>
            <div style="background: #f8fafc; padding: 15px; border-radius: 6px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: #64748b;">Total WAEC Fee:</span>
                    <span style="font-weight: 600;">{{ $currencySymbol }}{{ number_format($payment->candidate->total_fee, 2) }}</span>
                </div>
                <div style="display: flex; justify-between: space-between; margin-bottom: 8px;">
                    <span style="color: #64748b;">Total Paid:</span>
                    <span style="font-weight: 600; color: #10b981;">{{ $currencySymbol }}{{ number_format($payment->candidate->amount_paid, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 8px; border-top: 2px solid #e2e8f0;">
                    <span style="color: #64748b; font-weight: 600;">Balance Due:</span>
                    <span style="font-weight: 700; color: {{ $payment->candidate->balance > 0 ? '#ef4444' : '#10b981' }};">{{ $currencySymbol }}{{ number_format($payment->candidate->balance, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Bursar's Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Principal's Signature</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p style="color: #64748b; font-size: 12px; line-height: 1.6;">
                This is an official receipt for WAEC examination fees payment.<br>
                Receipt generated on {{ now()->format('F d, Y \a\t h:i A') }}<br>
                <strong>Note:</strong> This receipt is valid only when verified with the school administration.
            </p>
        </div>

        {{-- Print Button --}}
        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" class="print-button">🖨️ Print Receipt</button>
        </div>
    </div>
</body>
</html>
