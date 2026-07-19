<x-mail::message>
# Admission Application Received!

Dear **{{ $application->guardian_name }}**,

Thank you for applying to **{{ $schoolName }}**. We have successfully received the admission application for your child.

Here are the details of the submitted application:

## 📋 Application Reference: `{{ $application->application_no }}`

### **Student Details:**
- **Full Name:** {{ $application->first_name }} {{ $application->other_name }} {{ $application->last_name }}
- **Gender:** {{ ucfirst($application->gender) }}
- **Date of Birth:** {{ $application->dob ? \Carbon\Carbon::parse($application->dob)->format('F j, Y') : 'N/A' }}
- **Applied Class:** {{ $application->appliedClass->name ?? 'N/A' }}
- **Previous School:** {{ $application->previous_school ?? 'N/A' }}

### **Guardian Details:**
- **Guardian Name:** {{ $application->guardian_name }}
- **Email:** {{ $application->guardian_email }}
- **Phone:** {{ $application->guardian_phone }}
- **Address:** {{ $application->address ?? 'N/A' }}

---

### **Status:** `{{ ucfirst($application->status) }}`

Our admissions team will review the application and contact you soon with the next steps. If you have any questions or need to make corrections, please contact the school administration and quote your Application Reference.

Best regards,  
**{{ $schoolName }} Admissions Team**
</x-mail::message>
