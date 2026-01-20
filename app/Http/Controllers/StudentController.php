<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $student = auth()->user()->student;

        return Inertia::render('Student/Index', [
            'student' => $student,
        ]);
    }

    public function store(Request $request)
    {
        // تسجيل البيانات الواردة للتشخيص
        Log::info('Student store request', $request->all());

        $validated = $request->validate([
            'full_name_ar' => 'required|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'school_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'gpa' => 'required|numeric|min:0|max:100',
            'interests' => 'nullable|array',
            'skills' => 'nullable|array',
        ]);

        try {
            $user = auth()->user();

            $student = $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                $validated
            );

            Log::info('Student saved successfully', ['student_id' => $student->id]);

            return redirect()->route('student.index')
                ->with('success', 'تم حفظ الملف الشخصي بنجاح!');

        } catch (\Exception $e) {
            Log::error('Error in student store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'full_name_ar' => 'required|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'school_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'gpa' => 'required|numeric|min:0|max:100',
            'interests' => 'nullable|array',
            'skills' => 'nullable|array',
        ]);

        $student->update($validated);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح!');
    }
}
