<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\AssessmentResponse;
use App\Policies\StudentPolicy;
use App\Policies\AssessmentResponsePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Student::class => StudentPolicy::class,
        AssessmentResponse::class => AssessmentResponsePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
