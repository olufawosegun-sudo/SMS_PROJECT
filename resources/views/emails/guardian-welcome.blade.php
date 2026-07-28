<x-mail::message>
# Welcome to {{ $schoolName }}

Dear {{ $guardian->user->first_name }} {{ $guardian->user->last_name }},

Thank you for being part of the {{ $schoolName }} community. Your parent portal account has been created to help you stay connected with your child's education.

## Your Account Details

**Login Email:** {{ $guardian->user->email }}  
**Temporary Password:** {{ $defaultPassword }}  
**Account Type:** {{ ucfirst($guardian->relationship ?? 'Parent/Guardian') }}

<x-mail::button :url="$loginUrl" color="success">
Access Parent Portal
</x-mail::button>

## Getting Started

1. Click the button above to access the parent portal
2. Log in with your email and temporary password
3. Change your password immediately for security
4. Complete your profile information
5. Review your linked children

## What You Can Access

Your parent portal provides real-time access to:

- Academic performance and grades
- Attendance and punctuality records
- Announcements and school updates
- Teacher communications
- Fee statements and payment options
- Report cards and certificates

## Need Assistance?

If you experience any issues accessing your account or have questions, please contact the school administration.

**Portal URL:** {{ $loginUrl }}

Thank you for your partnership in your child's educational journey.

Sincerely,  
{{ $schoolName }}
</x-mail::message>
