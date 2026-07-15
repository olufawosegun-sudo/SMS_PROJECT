<x-mail::message>
# Welcome to {{ $schoolName }}!

Dear **{{ $teacher->user->first_name }} {{ $teacher->user->last_name }}**,

We're excited to welcome you as a teacher at {{ $schoolName }}! Your account has been successfully created.

## 📧 Your Login Credentials

**Email:** {{ $teacher->user->email }}  
**Temporary Password:** `{{ $defaultPassword }}`  
**Staff Number:** {{ $teacher->staff_no }}

<x-mail::button :url="$loginUrl" color="success">
Login to Your Dashboard
</x-mail::button>

## 🔐 Important Security Steps

1. Click the button above to verify your email address
2. Login with your credentials
3. **Change your password immediately** for security
4. Complete your profile information

## 📱 Need Help?

If you have any questions or need assistance, please contact the school administration.

---

**Login URL:** {{ $loginUrl }}

Thank you for joining our team!

Best regards,  
**{{ $schoolName }} Administration**
</x-mail::message>
