<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentDocument extends Model
{
    protected $fillable = [
        'school_id',
        'student_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'uploaded_at',
        'status',
        'notes',
        'expiry_date',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    // Document type constants
    const TYPE_BIRTH_CERTIFICATE = 'birth_certificate';

    const TYPE_MEDICAL_RECORD = 'medical_record';

    const TYPE_PASSPORT_PHOTO = 'passport_photo';

    const TYPE_PREVIOUS_SCHOOL_RECORD = 'previous_school_record';

    const TYPE_IMMUNIZATION_RECORD = 'immunization_record';

    const TYPE_PARENT_ID = 'parent_id';

    const TYPE_PROOF_OF_RESIDENCE = 'proof_of_residence';

    const TYPE_NATIONAL_ID = 'national_id';

    const TYPE_SPECIAL_NEEDS_DOC = 'special_needs_document';

    const TYPE_OTHER = 'other';

    /**
     * Get all available document types
     */
    public static function getDocumentTypes(): array
    {
        return [
            self::TYPE_BIRTH_CERTIFICATE => 'Birth Certificate',
            self::TYPE_MEDICAL_RECORD => 'Medical Record',
            self::TYPE_PASSPORT_PHOTO => 'Passport Photo',
            self::TYPE_PREVIOUS_SCHOOL_RECORD => 'Previous School Record',
            self::TYPE_IMMUNIZATION_RECORD => 'Immunization Record',
            self::TYPE_PARENT_ID => 'Parent/Guardian ID',
            self::TYPE_PROOF_OF_RESIDENCE => 'Proof of Residence',
            self::TYPE_NATIONAL_ID => 'National ID/Passport',
            self::TYPE_SPECIAL_NEEDS_DOC => 'Special Needs Documentation',
            self::TYPE_OTHER => 'Other',
        ];
    }

    /**
     * Get the student that owns this document.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the school that owns this document.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who uploaded this document.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if document is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if document has expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if document is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && $this->expiry_date->isFuture() && $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get human-readable document type
     */
    public function getDocumentTypeLabel(): string
    {
        $types = self::getDocumentTypes();

        return $types[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }

    /**
     * Get file extension
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        return in_array(strtolower($this->getFileExtension()), $imageExtensions);
    }

    /**
     * Check if file is a PDF
     */
    public function isPdf(): bool
    {
        return strtolower($this->getFileExtension()) === 'pdf';
    }

    /**
     * Get formatted file size (KB, MB)
     */
    public function getFormattedFileSize(): string
    {
        if (! $this->file_size) {
            return 'Unknown';
        }

        $sizeInKb = (int) $this->file_size;

        if ($sizeInKb < 1024) {
            return $sizeInKb.' KB';
        }

        return round($sizeInKb / 1024, 2).' MB';
    }

    /**
     * Get download URL
     */
    public function getDownloadUrl(): string
    {
        return route('student-documents.download', $this->id);
    }

    /**
     * Get view URL
     */
    public function getViewUrl(): string
    {
        return route('student-documents.view', $this->id);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(): bool
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->delete($this->file_path);
        }

        return false;
    }

    /**
     * Scope: Active documents only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Documents by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope: Expired documents
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    /**
     * Scope: Expiring soon (within 30 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30));
    }
}
