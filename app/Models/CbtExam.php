<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model {
    protected $table = 'cbt_exams';

    protected $fillable = [
        'school_id', 'session_id', 'term_id', 'class_id',
        'subject_id', 'assessment_id', 'title', 'duration', 'total_marks', 'start_time', 'end_time', 'status',
        'created_by', 'submitted_at', 'submitted_by',
        'approved_at', 'approved_by',
        'rejected_at', 'rejected_by', 'rejection_reason',
        'principal_comment', 'returned_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'returned_at' => 'datetime'
    ];

    public function assessment() {
        return $this->belongsTo(ContinuousAssessment::class, 'assessment_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function session() {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }
    
    public function term() {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }
    
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function submittedBy() {
        return $this->belongsTo(User::class, 'submitted_by');
    }
    
    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function rejectedBy() {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    
    // Helper methods
    public function canEdit()
    {
        return in_array($this->status, ['draft', 'needs_revision']);
    }
    
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-700',
            'pending_approval' => 'bg-amber-100 text-amber-700',
            'approved' => 'bg-green-100 text-green-700',
            'needs_revision' => 'bg-orange-100 text-orange-700',
            'rejected' => 'bg-red-100 text-red-700',
            'scheduled' => 'bg-blue-100 text-blue-700',
            'active' => 'bg-purple-100 text-purple-700',
            'completed' => 'bg-gray-400 text-white',
            default => 'bg-gray-100 text-gray-600'
        };
    }
    
    public function getStatusLabel()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'needs_revision' => 'Needs Revision',
            'rejected' => 'Rejected',
            'scheduled' => 'Scheduled',
            'active' => 'Active',
            'completed' => 'Completed',
            default => ucfirst($this->status)
        };
    }
}