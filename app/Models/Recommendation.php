<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    protected $fillable = [
        'student_id',
        'major_id',
        'match_score',
        'factors',
        'reasoning_ar',
        'reasoning_en',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'factors' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * Get strengths from factors
     */
    public function getStrengthsAttribute(): array
    {
        return $this->factors['strengths'] ?? [];
    }

    /**
     * Get challenges from factors
     */
    public function getChallengesAttribute(): array
    {
        return $this->factors['challenges'] ?? [];
    }

    /**
     * Scope to get top recommendations
     */
    public function scopeTopMatches($query, int $limit = 5)
    {
        return $query->orderBy('match_score', 'desc')->limit($limit);
    }

    /**
     * Scope to get recommendations above threshold
     */
    public function scopeAboveThreshold($query, float $threshold = 60.0)
    {
        return $query->where('match_score', '>=', $threshold);
    }
}
