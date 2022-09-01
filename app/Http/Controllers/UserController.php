<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UserRequest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected User $userModel;
    protected Student $studentModel;
    protected Teacher $teacherModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->teacherModel = new Teacher();
    }

    public function searchUser(Request $request) : JsonResponse
    {
        return response()->json($this->userModel->searchUser($request->searchString));
    }

    public function createOrUpdate(UserRequest $request, int $userId) : JsonResponse
    {
        $this->authorize('manage-users');
        // if userId is 0 or below, then create new user
        // else search that user
        if($userId > 0)
        {
            $user = $this->userModel->getUser($userId);
            if($user == null)
                return response()->json(['error' => 'User does not exists'], 404);
        }
        else
        {
            $user = new User();
        }

        // we are not using validated() there, because it will put all inputs to User model (teacher or student inputs also), even if we are creating teacher or student
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->identification_number = $request->identification_number;
        $user->email = $request->email;
        if($request->password != null) $user->password = Hash::make($request->password);
        $user->account_role = $request->account_role;
        $user->active = ($request->active == null ? 0 : 1);
        $user->locale = $request->userLocale;
        $user->save();

        if($user->account_role == 'student')
        {
            $students = array();
            foreach($request->student as $student_info)
            {
                $student = new Student();
                $student->user_ID = $user->id;
                $student->field_of_study = $student_info['field_of_study'];
                $student->semester = $student_info['semester'];
                $student->year_of_study = $student_info['year_of_study'];
                $student->mode_of_study = $student_info['mode_of_study'];

                $students[] = $student;
            }

            $user->student()->delete();
            $user->teacher()->delete();
            $user->student()->saveMany($students);
        }
        else if($user->account_role == 'teacher')
        {
            $teacher = $this->teacherModel->getTeacherByUserId($user->id);
            if($teacher == null)
                $teacher = new Teacher();

            $teacher->scien_degree = $request->teacher['scien_degree'];
            $teacher->business_email = $request->teacher['business_email'];
            $teacher->contact_number = $request->teacher['contact_number'] ?? null;
            $teacher->room = $request->teacher['room'] ?? null;
            $teacher->consultation_hours = $request->teacher['consultation_hours'] ?? null;
            $teacher->user_ID = $user->id;

            $user->student()->delete();
            $teacher->save();
        }

        return response()->json(['success' => 'User created successfully', 'user_ID' => $user->id]);
    }


    public function getUserProfile(int $userId = 0): JsonResponse
    {
        if($userId == 0)
            $userId = Auth::id();

        $user = $this->userModel->getUserRichInfo($userId);
        if($user == null)
            return response()->json(['error' => 'User does not exists!'], 404);

        return response()->json($user);
    }

    public function updateTeacher(TeacherRequest $request) : JsonResponse
    {
        $teacher = $this->teacherModel->getTeacherByUserId(Auth::id());
        if($teacher == null)
            return response()->json(['error' => 'This user probably isn\'t the teacher'], 404);

        $teacher->update($request->validated());

        return response()->json(['success' => 'Teacher information\'s updated successfully']);
    }

    public function delete(int $userId) : JsonResponse
    {
        $this->authorize('manage-users');
        $user = $this->userModel->getUser($userId);
        if($user == null)
            return response()->json(['error' => 'User does not exists!'], 404);

        $user->delete();

        return response()->json(['success' => 'User removed successfully']);
    }

    public function getAllTeachers($allTeachers = false): JsonResponse
    {
        return response()->json($this->userModel->getTeachers($allTeachers));
    }

}
