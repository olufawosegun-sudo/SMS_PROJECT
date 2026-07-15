<x-mail::message>
# Welcome to {{ $schoolName }}!

Dear **{{ $guardian->user->first_name }} {{ $guardian->user->last_name }}**,

Welcome to the {{ $schoolName }} family! Your parent/guardian account has been successfully created.

## 📧 Your Login Credentials

**Email:** {{ $guardian->user->email }}  
**Temporary Password:** `{{ $defaultPassword }}`  
**Relationship:** {{ ucfirst($guardian->relationship ?? 'Parent/Guardian') }}

<x-mail::button :url="$loginUrl" color="success">
Login to Your Dashboard
</x-mail::button>

## 🔐 Important Security Steps

1. Click the button above to verify your email address
2. Login with your credentials
3. **Change your password immediately** for security
4. Complete your profile information
5. Link your child(ren) to your account (if not already done)

## 📱 What You Can Do

With your parent/guardian account, you can:
- View your child(ren)'s academic progress
- Check attendance records
- Receive important announcements
- Communicate with teachers
- Make fee payments
- Download report cards

## 📞 Need Help?

If you have any questions or need assistance, please contact the school office.

---

**Login URL:** {{ $loginUrl }}

We look forward to partnering with you in your child's education!

Best regards,  
**{{ $schoolName }} Administration**
</x-mail::message>
