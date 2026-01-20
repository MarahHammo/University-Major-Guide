<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_id',
        'answers',
        'score',
        'time_taken',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'time_taken' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
