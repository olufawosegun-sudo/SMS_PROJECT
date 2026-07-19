<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\AdmissionApplication;
use App\Models\AdmissionOffer;
use App\Mail\AdmissionAppliedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdmissionApplicationController extends Controller
{
    /**
     * Display the public application form.
     */
    public function showForm()
    {
        $schools = School::all();
        $classes = SchoolClass::where('status', 'active')->get();

        return view('apply', compact('schools', 'classes'));
    }

    /**
     * Handle the application submission.
     */
    public function submitForm(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'applied_class_id' => 'required|exists:classes,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'previous_school' => 'nullable|string|max:255',
            // Document validation
            'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passport_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'school_report' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Generate a unique application number
        $year = date('Y');
        $count = AdmissionApplication::whereYear('created_at', $year)->count() + 1;
        $applicationNo = 'APP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Double check uniqueness
        while (AdmissionApplication::where('application_no', $applicationNo)->exists()) {
            $count++;
            $applicationNo = 'APP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        // Save application
        $application = AdmissionApplication::create([
            'school_id' => $request->school_id,
            'application_no' => $applicationNo,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'other_name' => $request->other_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'guardian_email' => $request->guardian_email,
            'address' => $request->address,
            'previous_school' => $request->previous_school,
            'applied_class_id' => $request->applied_class_id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Handle document uploads
        $documents = [
            'birth_certificate' => 'Birth Certificate',
            'passport_photo' => 'Passport Photograph',
            'school_report' => 'Previous School Report Card',
            'medical_certificate' => 'Medical Fitness Certificate',
        ];

        foreach ($documents as $fieldName => $documentName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $fileName = $applicationNo . '_' . str_replace(' ', '_', strtolower($documentName)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('admission-documents', $fileName, 'public');

                // Create document record
                \App\Models\AdmissionDocument::create([
                    'application_id' => $application->id,
                    'document_name' => $documentName,
                    'file' => $filePath,
                    'uploaded_at' => now(),
                ]);
            }
        }

        // Send confirmation email
        try {
            Mail::to($application->guardian_email)->send(new AdmissionAppliedMail($application));
        } catch (\Exception $e) {
            // Log warning but continue so the user's submission is not lost
            logger()->warning('Failed to send admission confirmation email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success_application', [
            'application_no' => $application->application_no,
            'student_name' => $application->first_name . ' ' . $application->last_name,
            'guardian_email' => $application->guardian_email,
        ]);
    }

    /**
     * Show offer acceptance page
     */
    public function showOfferAcceptance($offerId)
    {
        $offer = AdmissionOffer::with(['application.school', 'application.appliedClass', 'offeredClass'])
            ->findOrFail($offerId);

        // Check if offer is still pending
        if ($offer->status !== 'pending') {
            return view('offer-response', [
                'offer' => $offer,
                'alreadyResponded' => true
            ]);
        }

        // Check if offer has expired (14 days from offer date)
        $expiryDate = $offer->offer_date->addDays(14);
        $isExpired = now()->isAfter($expiryDate);

        return view('offer-acceptance', [
            'offer' => $offer,
            'application' => $offer->application,
            'school' => $offer->application->school,
            'offeredClass' => $offer->offeredClass,
            'expiryDate' => $expiryDate,
            'isExpired' => $isExpired
        ]);
    }

    /**
     * Handle offer acceptance or decline
     */
    public function handleOfferResponse(Request $request, $offerId)
    {
        $request->validate([
            'response' => 'required|in:accept,decline',
            'guardian_confirmation' => 'required|accepted',
        ]);

        $offer = AdmissionOffer::with('application.school')->findOrFail($offerId);

        // Check if offer is still pending
        if ($offer->status !== 'pending') {
            return redirect()->route('offer.show', $offerId)
                ->with('error', 'This offer has already been responded to.');
        }

        // Update offer status
        if ($request->response === 'accept') {
            $offer->update([
                'status' => 'accepted',
                'accepted_at' => now()
            ]);

            $message = 'Congratulations! You have successfully accepted the admission offer. The school will contact you shortly with the next steps.';
        } else {
            $offer->update([
                'status' => 'declined',
            ]);

            $message = 'You have declined the admission offer. Thank you for considering ' . $offer->application->school->name . '.';
        }

        return view('offer-response', [
            'offer' => $offer,
            'application' => $offer->application,
            'school' => $offer->application->school,
            'message' => $message,
            'accepted' => $request->response === 'accept'
        ]);
    }
}
