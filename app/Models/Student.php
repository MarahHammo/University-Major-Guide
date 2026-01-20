<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'full_name_ar',
        'full_name_en',
        'phone',
        'school_name',
        'gender',
        'birth_date',
        'gpa',
        'interests',
        'skills',
        'personality_traits',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'gpa' => 'decimal:2',
        'interests' => 'array',
        'skills' => 'array',
        'personality_traits' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessmentResponses()
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function assessmentResults()
{
    return $this->hasMany(AssessmentResponse::class);
}

/**
 * Get completed assessments count
 */
public function getCompletedAssessmentsCount()
{
    return $this->assessmentResults()->count();
}
}
