<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Recommendation;

class Major extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'category',
        'min_gpa',
        'required_skills',
        'career_prospects',
        'universities',
        'icon',
        'color',
    ];

    protected $casts = [
        'min_gpa' => 'decimal:2',
        'required_skills' => 'array',
        'career_prospects' => 'array',
        'universities' => 'array',
    ];

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

