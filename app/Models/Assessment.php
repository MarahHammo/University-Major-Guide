<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'type',
        'duration',
        'total_questions',
        'icon',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'total_questions' => 'integer',
    ];

    /**
     * Get all responses for this assessment
     */
    public function responses()
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    /**
     * Get responses for a specific student
     */
    public function studentResponses($studentId)
    {
        return $this->responses()->where('student_id', $studentId);
    }

    /**
     * Check if assessment is completed by student
     */
    public function isCompletedBy($studentId)
    {
        return $this->responses()
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage()
    {
        $totalStudents = \App\Models\Student::count();
        if ($totalStudents === 0) return 0;

        $completedCount = $this->responses()
            ->distinct('student_id')
            ->count('student_id');

        return round(($completedCount / $totalStudents) * 100);
    }

    /**
     * Get student's response for this assessment
     */
    public function getStudentResponse($studentId)
    {
        return $this->responses()
            ->where('student_id', $studentId)
            ->first();
    }
}
