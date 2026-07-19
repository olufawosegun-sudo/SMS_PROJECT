<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Offer Letter - {{ $application->application_no }}</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1B6B3E;
            padding-bottom: 20px;
        }
        .school-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1B6B3E;
            margin-bottom: 5px;
        }
        .school-info {
            font-size: 10pt;
            color: #666;
        }
        .letter-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            color: #1B6B3E;
            margin: 30px 0 20px 0;
            text-decoration: underline;
        }
        .ref-number {
            text-align: right;
            font-size: 9pt;
            color: #666;
            margin-bottom: 20px;
        }
        .date {
            margin-bottom: 30px;
        }
        .recipient {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .highlight {
            background-color: #f0f9f4;
            padding: 15px;
            border-left: 4px solid #1B6B3E;
            margin: 20px 0;
        }
        .highlight strong {
            color: #1B6B3E;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th,
        .details-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .details-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 40%;
        }
        .requirements {
            margin: 20px 0;
        }
        .requirements ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        .requirements li {
            margin-bottom: 8px;
        }
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin: 60px 20px 5px 20px;
            padding-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .acceptance-notice {
            background-color: #fff9e6;
            border: 2px solid #D4A843;
            padding: 15px;
            margin-top: 30px;
            text-align: center;
        }
        .acceptance-notice strong {
            color: #D4A843;
            font-size: 12pt;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div class="school-info">
            {{ $school->address }}<br>
            {{ $school->city }}, {{ $school->state }}, {{ $school->country }}<br>
            Email: {{ $school->email }} | Phone: {{ $school->phone }}
        </div>
    </div>

    {{-- Reference Number --}}
    <div class="ref-number">
        Ref: {{ $school->code ?? 'SCH' }}/ADM/{{ date('Y') }}/{{ $application->application_no }}
    </div>

    {{-- Date --}}
    <div class="date">
        <strong>Date:</strong> {{ now()->format('F d, Y') }}
    </div>

    {{-- Recipient --}}
    <div class="recipient">
        {{ $application->guardian_name }}<br>
        {{ $application->address }}
    </div>

    {{-- Letter Title --}}
    <div class="letter-title">ADMISSION OFFER LETTER</div>

    {{-- Salutation --}}
    <div class="content">
        <p>Dear {{ $application->guardian_name }},</p>
    </div>

    {{-- Main Content --}}
    <div class="content">
        <p>
            <strong>RE: ADMISSION OFFER FOR {{ strtoupper($application->first_name) }} {{ strtoupper($application->last_name) }}</strong>
        </p>

        <p>
            Further to your application for admission into our esteemed institution, we are pleased to inform you that 
            <strong>{{ $application->first_name }} {{ $application->last_name }} {{ $application->other_name }}</strong> 
            has been offered provisional admission into <strong>{{ $offeredClass->name }}</strong> 
            for the <strong>{{ $currentSession->name ?? date('Y') . '/' . (date('Y') + 1) }}</strong> academic session.
        </p>

        <p>
            This offer is made in recognition of the student's potential and our belief that they will contribute positively 
            to the academic and social life of our school community.
        </p>
    </div>

    {{-- Student Details --}}
    <div class="highlight">
        <strong>STUDENT DETAILS</strong>
    </div>

    <table class="details-table">
        <tr>
            <th>Application Number</th>
            <td>{{ $application->application_no }}</td>
        </tr>
        <tr>
            <th>Student Name</th>
            <td>{{ $application->first_name }} {{ $application->last_name }} {{ $application->other_name }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $application->dob?->format('F d, Y') }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $application->gender }}</td>
        </tr>
        <tr>
            <th>Class Offered</th>
            <td><strong>{{ $offeredClass->name }} ({{ $offeredClass->level }})</strong></td>
        </tr>
        <tr>
            <th>Academic Session</th>
            <td>{{ $currentSession->name ?? date('Y') . '/' . (date('Y') + 1) }} Academic Year</td>
        </tr>
    </table>

    {{-- Acceptance Requirements --}}
    <div class="highlight">
        <strong>ACCEPTANCE REQUIREMENTS</strong>
    </div>

    <div class="requirements">
        <p>To confirm acceptance of this offer, you are required to:</p>
        <ul>
            <li>Complete the acceptance form online or visit the school within <strong>14 days</strong> of receiving this letter</li>
            <li>Pay the admission acceptance fee and first term fees as outlined in the fee structure</li>
            <li>Submit original copies of all required documents including birth certificate and previous school records</li>
            <li>Complete medical examination form (provided by the school)</li>
            <li>Purchase prescribed school uniforms and learning materials</li>
        </ul>

        <p><strong>Important:</strong> Failure to accept this offer within the stipulated time frame will result in the automatic 
        withdrawal of the offer, and the slot may be offered to another qualified applicant.</p>
    </div>

    {{-- Next Steps --}}
    <div class="highlight">
        <strong>NEXT STEPS</strong>
    </div>

    <div class="requirements">
        <ol>
            <li>Check your email for the acceptance link or visit: <strong>{{ url('/accept-offer/' . $offer->id) }}</strong></li>
            <li>Click "Accept Offer" to confirm your enrollment</li>
            <li>Pay the required fees through our payment portal</li>
            <li>Attend the orientation program (date will be communicated)</li>
        </ol>
    </div>

    {{-- Acceptance Notice --}}
    <div class="acceptance-notice">
        <strong>DEADLINE FOR ACCEPTANCE</strong><br>
        This offer must be accepted by <strong>{{ $offer->offer_date->addDays(14)->format('F d, Y') }}</strong>
    </div>

    {{-- Closing --}}
    <div class="content">
        <p>
            We look forward to welcoming {{ $application->first_name }} to our school community. Should you have any questions 
            regarding this offer, please do not hesitate to contact our admissions office.
        </p>

        <p>
            Congratulations once again, and we wish {{ $application->first_name }} a successful and fulfilling academic journey with us.
        </p>

        <p>Yours faithfully,</p>
    </div>

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Principal/Head of School<br>
                {{ $school->name }}
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Admissions Officer<br>
                {{ $school->name }}
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        This is an official document from {{ $school->name }}. For verification, contact {{ $school->email }}
    </div>
</body>
</html>
