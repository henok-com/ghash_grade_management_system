<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stream;
use App\Models\Courses;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Http\Middleware\isAdmin;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TeacherController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware() {
        return [
            new Middleware("auth:sanctum"),
            new Middleware(isAdmin::class),
        ];
    }
    public function index()
    {

        $response = [];
        foreach(Teacher::all() as $teacher) {
            $user = $teacher->user()->get();
            $teacher = $teacher->where("user_id", $user->get(0)->id)->get();
            $response[] = array_merge(...$teacher->toArray(), ...[0 => ["teacher_id" => $teacher->get(0)->id]], ...$user->toArray());
        }

        return new TeacherResource($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

     public function searchTeachers(string $name) {
        $name = Str::lower($name);
        $searchResults = [];
        foreach(Teacher::all() as $teacher) {
            $user = $teacher->user()->first();
            $full_name = $user->first_name . " " . $user->middle_name . " " . $user->last_name;
            $full_name = Str::lower($full_name);
            if(str_contains($full_name, $name)) {
                $searchResults[] = array_merge($user->toArray(), $teacher->toArray());
            }
        }   
        return new TeacherResource($searchResults);
     }

     public function teachersInCourse(Courses $course) {
        $teacherAssignments = $course->teacherAssignments()->get();
        $filterResults = [];
        foreach($teacherAssignments as $teacherAssignment) {
            $user = $teacherAssignment->user()->first();
            $teacher = $user->teacher()->first();
            $filterResults[] = array_merge($user->toArray(), $teacher->toArray());
        }
        return new TeacherResource($filterResults);
     } 
     public function teachersInSection(Section $section) {
        $teacherAssignments = $section->teacherAssignments()->get();
        $filterResults = [];
        foreach($teacherAssignments as $teacherAssignment) {
            $user = $teacherAssignment->user()->first();
            $teacher = $user->teacher()->first();
            $filterResults[] = array_merge($user->toArray(), $teacher->toArray());
        }
        return new TeacherResource($filterResults);
     } 


     public function teachersInStream(Stream $stream) {
        $teacherAssignments = $stream->teacherAssignments()->get();
        $filterResults = [];
        foreach($teacherAssignments as $teacherAssignment) {
            $user = $teacherAssignment->user()->first();
            $teacher = $user->teacher()->first();
            $filterResults[] = array_merge($user->toArray(), $teacher->toArray());
        }
        return new TeacherResource($filterResults);
     } 

    public function show(Teacher $teacher)
    {
        $response = [];
        $user = $teacher->user()->get();
        $teacher = $teacher->where("user_id", $user->get(0)->id)->get();
        $response[] = array_merge(...$teacher->toArray(), ...[0=> ["teacher_id"=> $teacher->get(0)->id]], ...$user->toArray());

        return new TeacherResource($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $validatedData = $request->validated();
        $department = Department::where("department_name", $validatedData['department_name'])->get();

        $teacher->update([
            "teacher_username" => $validatedData["teacher_username"],
        ]);

        $teacher->user()->update([
            "first_name" => $validatedData["first_name"],
            "middle_name" => $validatedData["middle_name"],
            "last_name" => $validatedData["last_name"],
            "age" => $validatedData["age"],
            "gender" => $validatedData["gender"],
            "address" => $validatedData["address"],
            "email" => $validatedData["email"],
            "phone_number" => $validatedData["phone_number"],
            "department_id" => $department->get(0)->id,
        ]);

        $response = [];
        $user = $teacher->user()->get();
        $teacher = $teacher->where("user_id", $user->get(0)->id)->get();
        $response[] = array_merge(...$teacher->toArray(), ...[0=> ["teacher_id"=> $teacher->get(0)->id]], ...$user->toArray());

        return new TeacherResource($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->user()->delete();
        $teacher->delete();

        return new TeacherResource(["messages" => "Teacher Deleted Successfully"]);
    }
}
