<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentLoginController extends Controller
{
    public function validateLogin(Request $request)
    {
        $studentId = $request->input('student_id');
        return Student::find($studentId);
    }

    public function checkLoggedIn()
    {
        return session()->has('student');
    }

    public function getStudentSession()
    {
        return session('student');
    }

    public function setStudentSession($student)
    {
        session([
            'student' => [
                'id' => $student->id,
                'name' => $student->name . ' ' . $student->surname,
            ],
        ]);
    }

    public function destroyStudentSession()
    {
        session()->forget('student');
    }
}
