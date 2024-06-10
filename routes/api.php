<?php

use Illuminate\Http\Request;
use App\Models\ProfilePicture;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LevelGradeController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\TeacherAssignmentController;

// Route::apiResource('users', UserController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('streams', StreamController::class);
Route::apiResource('assignTeacher', TeacherAssignmentController::class);
Route::apiResource('profilePictures', ProfilePictureController::class);
// Authentication and Authorization
Route::post('registerAdmin', [AuthController::class, 'registerAdmin']);
Route::post('registerStudent', [AuthController::class, 'registerStudent']);
Route::post('registerTeacher', [AuthController::class, 'registerTeacher']);
Route::post('firstAdmin', [AuthController::class,'firstAdmin']);
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout']);
Route::put('changePassword', [AuthController::class,'changePassword']);

// Admin
Route::get('reviewGrade/{user}', [GradeController::class, 'reviewGrade']);
Route::get('highScoredStudents/{level}', [LevelGradeController::class, 'highScoredStudents']);
Route::get('lowScoredStudents/{level}', [LevelGradeController::class, 'lowScoredStudents']);
Route::get('searchStudents/{name}', [StudentController::class, 'searchStudents']);
Route::get('searchStudents/{section}/{name}', [StudentController::class, 'searchStudentsInSection']);
Route::get('searchStudentsByStream/{stream}/{name}', [StudentController::class, 'searchStudentsInStream']);
Route::get('newStudents', [StudentController::class, 'newStudents']);
Route::get('hasPaid/{student}/{level}', [StudentController::class, 'studentPaid']);
Route::get('studentPaymentsInfo/{student}', [StudentController::class, 'studentPaymentsInfo']);
Route::get('searchTeachers/{name}', [TeacherController::class, "searchTeachers"]);
Route::get('teachersInCourse/{course}', [TeacherController::class, "teachersInCourse"]);
Route::get('teachersInSection/{section}', [TeacherController::class, "teachersInSection"]);
Route::get('teachersInStream/{stream}', [TeacherController::class, "teachersInStream"]);
Route::get('promoteTeacher/{teacher}', [AdminController::class, "promoteTeacher"]);
Route::get('depromoteTeacher/{admin}', [AdminController::class, "depromoteTeacher"]);
Route::post('sendMessage', [MessageController::class, 'sendMessage']);

