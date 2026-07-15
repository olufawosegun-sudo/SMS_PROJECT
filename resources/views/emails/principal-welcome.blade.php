<x-mail::message>
# Welcome to {{ $schoolName }}!

Dear **{{ $principal->user->first_name }} {{ $principal->user->last_name }}**,

We're honored to have you as a **{{ $principal->staff_type }}** at {{ $schoolName }}! Your leadership account has been successfully created.

## 📧 Your Login Credentials

**Email:** {{ $principal->user->email }}  
**Temporary Password:** `{{ $defaultPassword }}`  
**Staff Number:** {{ $principal->staff_no }}  
**Position:** {{ $principal->staff_type }}

<x-mail::button :url="$loginUrl" color="success">
Login to Your Dashboard
</x-mail::button>

## 🔐 Important Security Steps

1. Click the button above to access your account
2. Login with your credentials
3. **Change your password immediately** for security
4. Complete your profile information
5. Review your administrative dashboard

## 📱 Your Responsibilities

As {{ $principal->staff_type }}, you have access to:
- School management dashboard
- Staff and student records
- Academic session management
- Reports and analytics
- Administrative tools

## 📞 Need Help?

If you have any questions or need assistance, please contact the school administration team.

---

**Login URL:** {{ $loginUrl }}

Thank you for your leadership!

Best regards,  
**{{ $schoolName }} Administration**
</x-mail::message>
