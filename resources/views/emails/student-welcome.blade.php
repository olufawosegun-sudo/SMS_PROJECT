<x-mail::message>
# Welcome to {{ $schoolName }}!

Dear **{{ $student->user->first_name }} {{ $student->user->last_name }}**,

Congratulations! You are now officially a student at {{ $schoolName }}. Your student account has been successfully created.

## 📧 Your Login Credentials

**Email:** {{ $student->user->email }}  
**Temporary Password:** `{{ $defaultPassword }}`  
**Admission Number:** {{ $student->admission_no }}  
**Admission Date:** {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('F j, Y') : 'N/A' }}

<x-mail::button :url="$loginUrl" color="success">
Login to Your Dashboard
</x-mail::button>

## 🔐 Important Security Steps

1. Click the button above to access your account
2. Login with your credentials
3. **Change your password immediately** for security
4. Complete your profile information
5. Explore your student dashboard

## 📱 What You Can Access

With your student account, you can:
- View your class timetable
- Check your assignments and homework
- View your attendance records
- Access learning materials
- Check your grades and report cards
- Receive announcements from teachers

## 📚 Ready to Learn?

Your educational journey with us begins now. We're excited to support you in achieving your academic goals!

## 📞 Need Help?

If you have any questions or need assistance, please contact your class teacher or school office.

---

**Login URL:** {{ $loginUrl }}

Welcome aboard, and we wish you success in your studies!

Best regards,  
**{{ $schoolName }} Administration**
</x-mail::message>
