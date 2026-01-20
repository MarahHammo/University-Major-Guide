<?php

namespace App\Policies;

use App\Models\AssessmentResponse;
use App\Models\User;

class AssessmentResponsePolicy
{
    public function view(User $user, AssessmentResponse $response): bool
    {
        return $user->student && $user->student->id === $response->student_id;
    }
}
