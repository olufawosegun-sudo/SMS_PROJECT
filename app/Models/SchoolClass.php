<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'school_id',
        'school_branch_id',
        'name',
        'level',
        'category',
        'order_index',
        'description',
        'status',
    ];

    /**
     * Scope ordered classes by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope by educational category/stage
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function schoolBranch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }

    public function arms()
    {
        return $this->hasMany(ClassArm::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Human-friendly category badge label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'early_childhood' => 'Early Childhood / Pre-School',
            'primary' => 'Primary / Elementary',
            'junior_secondary' => 'Junior Secondary / Middle',
            'senior_secondary' => 'Senior Secondary / High School',
            'vocational' => 'Vocational & Technical',
            default => 'General Level',
        };
    }
}
