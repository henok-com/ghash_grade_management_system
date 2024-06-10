<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Stream;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Middleware\isAdmin;
use App\Http\Resources\AuthResource;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterStudentRequest;
use App\Http\Requests\RegisterTeacherRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware("auth:sanctum", except: ["firstAdmin", "login"]),
            new Middleware(isAdmin::class, only: ["registerAdmin", "registerStudent", "registerTeacher"]),
        ];
    }
    
    public function registerAdmin(RegisterAdminRequest $request) {
        $validatedData = $request->validated();
        $department_id = Department::where('department_name', $validatedData['department_name'])->get();
        $userData = [
            "first_name" => Str::lower($validatedData["first_name"]),
            "middle_name" => Str::lower($validatedData["middle_name"]),
            "last_name" => Str::lower($validatedData["last_name"]),
            "age" => $validatedData["age"],
            "gender" => Str::lower($validatedData["gender"]),
            "address" => Str::lower($validatedData["address"]),
            "email" => $validatedData["email"],
            "phone_number" => $validatedData["phone_number"],
            "department_id" => $department_id->get(0)->id,
        ];
        $user = User::create($userData);
        
        $admin_username = "ADM_" . $validatedData['department_name'] . "_" . $validatedData['first_name'] . "_" . $user->id;

        $admin = Admin::create([
            "user_id" => $user->id,
            "admin_username" => $admin_username,
            "password" => Str::password(8),
        ]);

        $token = $user->createToken("token")->plainTextToken;
        return new AuthResource(array_merge($user->toArray(), ["admin_id" => $admin->id],$admin->toArray(), ["token" => $token]));

    }

    public function registerTeacher(RegisterTeacherRequest $request) {
        $validatedData = $request->validated();
        $department_id = Department::where('department_name', $validatedData['department_name'])->get();
        $userData = [
            "first_name" => Str::lower($validatedData["first_name"]),
            "middle_name" => Str::lower($validatedData["middle_name"]),
            "last_name" => Str::lower($validatedData["last_name"]),
            "age" => $validatedData["age"],
            "gender" => Str::lower($validatedData["gender"]),
            "address" => Str::lower($validatedData["address"]),
            "email" => $validatedData["email"],
            "phone_number" => $validatedData["phone_number"],
            "department_id" => $department_id->get(0)->id,
        ];
        $user = User::create($userData);
        
        $teacher_username = "TEA_" . $validatedData['department_name'] . "_" . $validatedData['first_name'] . "_" . $user->id;

        $teacher = Teacher::create([
            "user_id" => $user->id,
            "teacher_username" => $teacher_username,
            "password" => Str::password(8),
        ]);

        $token = $user->createToken("token")->plainTextToken;
        return new AuthResource(array_merge($user->toArray(), ["teacher_id" => $teacher->id],$teacher->toArray(), ["token" => $token]));

    }

    public function registerStudent(RegisterStudentRequest $request) {
        $validatedData = $request->validated();
        $stream = Stream::where("stream_name", $validatedData['stream_name'])->get();
        $department = Department::where('department_name', $validatedData['department_name'])->get();
        $section = $stream->get(0)->sections()->exists() ? $stream->get(0)->sections()->latest()->get() : $stream->get(0)->sections()->latest()->get();
        $section_id_incrememnted = $section->isNotEmpty() ? $section->get(0)->id + 1 : 1;
        $section_id = $section->get(0)->id ?? null;
        $section_name = $section->get(0)->section_name ?? null;
        $current_students_amount = $section->get(0)->current_students_amount ?? null;
        $sectionUpdate = $section->get(0);
        if(!$stream->get(0)->sections()->exists() || $section->get(0)->current_students_amount >= $stream->get(0)->maximum_students_in_section) {
            $section = Section::create([
                "stream_id" => $stream->get(0)->id,
                "section_name" => $validatedData['department_name'] . "_" . $validatedData['stream_name']. "_" . $section_id_incrememnted,
                "current_students_amount" => 0
            ]);
            $section_id = $section->id;
            $section_name = $section->section_name;
            $current_students_amount = $section->current_students_amount;
            $sectionUpdate = $section;
        }


        $user = User::create([
            "first_name" => Str::lower($validatedData["first_name"]),
            "middle_name" => Str::lower($validatedData["middle_name"]),
            "last_name" => Str::lower($validatedData["last_name"]),
            "age" => $validatedData["age"],
            "gender" => Str::lower($validatedData["gender"]),
            "address" => Str::lower($validatedData["address"]),
            "email" => $validatedData["email"],
            "phone_number" => $validatedData["phone_number"],
            "department_id" => $department->get(0)->id,
        ]);
        $student_id_incremented = Student::latest()->get()->isNotEmpty() ? Student::latest()->get()->get(0)->id + 1 : 1;

        $student = Student::create([
            "user_id" => $user->id,
            "stream_id" => $stream->get(0)->id,
            "section_id" => $section_id,
            "level" => $validatedData['level'],
            "current_level" => $validatedData['current_level'],
            "student_username" => $validatedData['department_name'] . "_" . $stream->get(0)->stream_name . "_" . $student_id_incremented, 
            "password" => Str::password(12), 
        ]);

        $stream->get(0)->update([
            "current_students_amount" => $stream->get(0)->current_students_amount + 1
        ]);
        
        $sectionUpdate->update([
            "current_students_amount" => $current_students_amount + 1,
        ]);


        
        return new AuthResource(array_merge($user->toArray(), ["student_id" => $student->id],$student->toArray()));

    
    }

    public function firstAdmin(Request $request) {
        if(Admin::first() !== null ) {
            abort(404, "Invalid Route go to http://localhost:8000/api/registerAdmin");
        } else if(Department::first() === null) {
            abort(403, "First create Department. To create department go to http://localhost:8000/api/departments");
        } else {
            $validatedData = $request->validate([
                "first_name" => "required|string|max:100",
                "middle_name" => "required|string|max:100",
                "last_name" => "required|string|max:100",
                "age" => "required|integer|max:100|min:15",
                "gender" => "required|string|max:10",
                "address" => "required|string",
                "email" => "required|email:rfc|unique:App\Models\User,email",
                "phone_number" => "required|string",
                "password" => ["required", "string", Password::min(8)->letters()->mixedCase()->symbols()->numbers()],
                "user_type" => "required|string"
            ]);

            if($validatedData['user_type'] !== "admin") {
                abort(401, "Unauthorized");
            } else {
                $department = Department::first();
                $admin_username = "ADM_" . $department->department_name . "_" . $validatedData['first_name'] . "_1";
                $userData = [
                    "first_name" => Str::lower($validatedData["first_name"]),
                    "middle_name" => Str::lower($validatedData["middle_name"]),
                    "last_name" => Str::lower($validatedData["last_name"]),
                    "age" => $validatedData["age"],
                    "gender" => Str::lower($validatedData["gender"]),
                    "address" => Str::lower($validatedData["address"]),
                    "email" => $validatedData["email"],
                    "phone_number" => $validatedData["phone_number"],
                    "department_id" => $department->id,
                ];

                
                $user = User::create($userData);

                $admin = Admin::create([
                    "user_id" => $user->id,
                    "admin_username" => $admin_username,
                    "password" => $validatedData['password'],
                ]);

                $token = $user->createToken("token")->plainTextToken;
                return new AuthResource(array_merge($user->toArray(), ["admin_id" => $admin->id],$admin->toArray(), ["token" => $token]));
            }
        }

    }

    public function login(Request $request) {
        $validatedData = $request->validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);
        
        if(str_contains($validatedData['username'], "ADM")) {
            $admin = Admin::where("admin_username", $validatedData["username"])->get();
            $token = $admin->get(0)->user()->get()->get(0)->createToken("token")->plainTextToken;
            if($admin && $admin->get(0)->password === $validatedData["password"]) {
                return new AuthResource(["messages" => "Login Successfull", "token" => $token]);
            } else {
                return response()->json(new AuthResource(["messages" => "Username or Password Incorrect"]), 401);
            }
        } else if(str_contains($validatedData['username'], "TEA")) {
            $teacher = Teacher::where("teacher_username", $validatedData['username'])->get();
            $token = $teacher->get(0)->user()->get()->get(0)->createToken('token')->plainTextToken;
            if($teacher && $teacher->get(0)->password === $validatedData['password']) {
                return new AuthResource(['messages'=> 'Login Successfull', "token" => $token]);
            } else {
                return response()->json(new AuthResource(["messages" => "Username or Password Incorrect"]), 401);
            }
        } else {
            $student = Student::where("student_username", $validatedData['username'])->get();
            $token = $student->get(0)->user()->get()->get(0)->createToken("token")->plainTextToken;
            if($student && $student->get(0)->password === $validatedData['password']) {
                return new AuthResource(['messages' => "Login Successfull", "token" => $token]);
            } else {
                return response()->json(new AuthResource(['messages' => "Username or Password Incorrect"]), 401);
            }
        }
    }
    
    public function changePassword(ChangePasswordRequest $request) {
        $validatedData = $request->validated();
            if($request->user()->admin()->exists()) {
                if($validatedData['old_password'] !== $request->user()->admin()->get()->get(0)->password) {
                    return response()->json(new AuthResource(["messages" => "The provided old password is incorrect"]), 422);
                }
                $request->user()->admin()->get()->get(0)->update(["password" => $validatedData["new_password"]]);
                return new AuthResource(["messages" => "Password Changed Successfully"]);
            } else if ($request->user()->teacher()->exists()) {
                if($validatedData['old_password'] !== $request->user()->teacher()->get()->get(0)->password) {
                    return response()->json(new AuthResource(["messages" => "The provided old password is incorrect"]), 422);
                }
                $request->user()->teacher()->get()->get(0)->update(["password" => $validatedData["new_password"]]);
                return new AuthResource(["messages" => "Password Changed Successfully"]);
            } else if ($request->user()->student()->exists()) {
                if($validatedData['old_password'] !== $request->user()->student()->get()->get(0)->password) {
                    return response()->json(new AuthResource(["messages" => "The provided old password is incorrect"]), 422);
                }
                $request->user()->student()->get()->get(0)->update(["password" => $validatedData["new_password"]]);
                return new AuthResource(["messages" => "Password Changed Successfully"]);
            }
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->noContent();
    }
}
