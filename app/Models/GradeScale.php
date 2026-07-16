<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model {
    protected $fillable = ['grading_system_id', 'min_score', 'max_score', 'grade', 'remark'];

    protected $casts = [
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2'
    ];

    public function results() {
        return $this->hasMany(Result::class);
    }

    public function gradingSystem() {
        return $this->belongsTo(GradingSystem::class);
    }
}