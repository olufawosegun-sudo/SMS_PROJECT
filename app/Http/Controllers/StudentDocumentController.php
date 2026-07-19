<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentDocumentController extends Controller
{
    /**
     * Display all student documents (Owner access)
     */
    public function all(Request $request)
    {
        // Only Owner can access this
        if (Auth::user()->role->name !== 'Owner') {
            abort(403, 'Unauthorized access');
        }

        $school = Auth::user()->school;
        $role = Auth::user()->role->name ?? 'Owner';

        // Base query
        $query = StudentDocument::where('school_id', $school->id)
            ->with(['student.user', 'student.schoolClass', 'uploader']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhereHas('student', function($q) use ($search) {
                $q->where('admission_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'expired') {
                $query->whereNotNull('expiry_date')
                      ->where('expiry_date', '<', now());
            } elseif ($request->status === 'expiring') {
                $query->whereNotNull('expiry_date')
                      ->where('expiry_date', '<=', now()->addDays(30))
                      ->where('expiry_date', '>', now());
            } elseif ($request->status === 'active') {
                $query->where(function($q) {
                    $q->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>', now()->addDays(30));
                });
            }
        }

        // Get paginated documents
        $documents = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_documents' => StudentDocument::where('school_id', $school->id)->count(),
            'missing_birth_certificates' => Student::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereDoesntHave('documents', function($q) {
                    $q->where('document_type', StudentDocument::TYPE_BIRTH_CERTIFICATE);
                })
                ->count(),
            'expiring_soon' => StudentDocument::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->count(),
            'expired' => StudentDocument::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now())
                ->count(),
        ];

        return view('student-documents.index', compact('documents', 'stats', 'school', 'role'));
    }

    /**
     * Display documents for a specific student
     */
    public function index($studentId)
    {
        $student = Student::with(['documents' => function($query) {
            $query->active()->latest('uploaded_at');
        }])->findOrFail($studentId);

        // Check permission
        $this->authorizeView($student);

        return view('student-documents.index', compact('student'));
    }

    /**
     * Store a newly uploaded document
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'document_type' => 'required|string',
            'document_name' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:5120', // Max 5MB
            'notes' => 'nullable|string|max:1000',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $student = Student::findOrFail($request->student_id);
        
        // Check permission
        $this->authorizeUpload($student);

        // Handle file upload
        $file = $request->file('file');
        $fileName = $this->generateFileName($file, $request->document_type);
        $filePath = $file->storeAs(
            "student_documents/{$student->school_id}/{$student->id}",
            $fileName,
            'public'
        );

        // Get file size in KB
        $fileSizeKb = round($file->getSize() / 1024, 2);

        // Create document record
        $document = StudentDocument::create([
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'document_type' => $request->document_type,
            'document_name' => $request->document_name ?? StudentDocument::getDocumentTypes()[$request->document_type] ?? 'Document',
            'file_path' => $filePath,
            'file_size' => $fileSizeKb,
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now(),
            'status' => 'active',
            'notes' => $request->notes,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    /**
     * View/preview a document
     */
    public function view($id)
    {
        $document = StudentDocument::with('student')->findOrFail($id);
        
        // Check permission
        $this->authorizeView($document->student);

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = $document->mime_type ?? Storage::disk('public')->mimeType($document->file_path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($document->file_path) . '"'
        ]);
    }

    /**
     * Download a document
     */
    public function download($id)
    {
        $document = StudentDocument::with('student')->findOrFail($id);
        
        // Check permission
        $this->authorizeView($document->student);

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->document_name . '.' . $document->getFileExtension()
        );
    }

    /**
     * Delete a document
     */
    public function destroy($id)
    {
        $document = StudentDocument::with('student')->findOrFail($id);
        
        // Check permission - only owner can delete
        if (Auth::user()->role->name !== 'Owner') {
            return redirect()->back()->with('error', 'You do not have permission to delete documents.');
        }

        // Delete file from storage
        $document->deleteFile();

        // Delete database record
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Check if user can view student documents
     */
    private function authorizeView(Student $student)
    {
        $user = Auth::user();
        $role = $user->role->name;

        // Owner can view all
        if ($role === 'Owner') {
            return true;
        }

        // Principal can view students in their school
        if ($role === 'Principal' || $role === 'Vice Principal' || $role === 'Assistant Principal') {
            if ($user->staff && $user->staff->school_id === $student->school_id) {
                return true;
            }
        }

        // Teacher can view students in their classes
        if ($role === 'Teacher') {
            $teacherStaff = $user->staff;
            if ($teacherStaff) {
                // Check if teacher teaches this student's class
                $teachesStudent = \DB::table('teacher_subjects')
                    ->where('staff_id', $teacherStaff->id)
                    ->where('class_id', $student->class_id)
                    ->exists();
                
                if ($teachesStudent) {
                    return true;
                }
            }
        }

        // Guardian can view their own child's documents
        if ($role === 'Guardian') {
            $guardian = $user->guardian;
            if ($guardian && $guardian->students()->where('students.id', $student->id)->exists()) {
                return true;
            }
        }

        abort(403, 'Unauthorized to view these documents.');
    }

    /**
     * Check if user can upload documents
     */
    private function authorizeUpload(Student $student)
    {
        $user = Auth::user();
        $role = $user->role->name;

        // Owner can upload for all
        if ($role === 'Owner') {
            return true;
        }

        // Guardian can upload for their own child
        if ($role === 'Guardian') {
            $guardian = $user->guardian;
            if ($guardian && $guardian->students()->where('students.id', $student->id)->exists()) {
                return true;
            }
        }

        abort(403, 'Unauthorized to upload documents for this student.');
    }

    /**
     * Generate secure filename
     */
    private function generateFileName($file, $documentType): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        return "{$documentType}_{$timestamp}_{$random}.{$extension}";
    }
}
