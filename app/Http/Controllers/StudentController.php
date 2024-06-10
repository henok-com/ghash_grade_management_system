<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stream;
use App\Models\Section;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Http\Middleware\isAdmin;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class StudentController extends Controller implements HasMiddleware
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
        foreach(Student::all() as $student) {
            $user = $student->user()->get();
            $student = $student->where('user_id', $user->get(0)->id)->get();
            $response[] = array_merge(...$student->toArray(), ...[0 => ["student_id" => $student->get(0)->id]], ...$user->toArray(),);
        }
        
        
        return new StudentResource($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        //
    }

    public function searchStudents(string $name) {
        $name = Str::lower($name);
        $students = [];
        $searchResult = [];
        foreach(User::all() as $user) {
            if($user->student()->exists()) {
                $students[] = $user;
            }
        }
        foreach($students as $student) {
            $full_name = $student->first_name . " " . $student->middle_name . " " . $student->last_name; 
            $full_name = Str::lower($full_name);
            if(str_contains($full_name, $name)) {
                $student_info = $student->student()->first();
                $response = array_merge(
                    $student->toArray(),
                    $student_info->toArray());
                $searchResult[] = $response;
            }
        }
        return new StudentResource($searchResult);
    }

    public function searchStudentsInSection(Section $section, string $name) {
        $name = Str::lower($name);
        $students = $section->students()->get();
        $searchResult = [];
        foreach($students as $student) {
            if($student->user()->exists()) {
                $user = $student->user()->get()->get(0);
                $full_name = $user->first_name . " " . $user->middle_name . " " . $user->last_name;
                $full_name = Str::lower($full_name);
                if(str_contains($full_name, $name)) {
                    $searchResult[] = array_merge(
                        $user->toArray(),
                        $student->toArray()
                    );
                }   
            }
        }
        return new StudentResource($searchResult);
    }
    
    public function searchStudentsInStream(Stream $stream, string $name) {
        $name = Str::lower($name);
        $students = $stream->students()->get();
        $searchResult = [];
        foreach($students as $student) {
            if($student->user()->exists()) {
                $user = $student->user()->get()->get(0);
                $full_name = $user->first_name . " " . $user->middle_name . " " . $user->last_name;
                $full_name = Str::lower($full_name);
                if(str_contains($full_name, $name)) {
                    $searchResult[] = array_merge(
                        $user->toArray(),
                        $student->toArray()
                    );
                }   
            }
        }
        return new StudentResource($searchResult);
    }


    public function newStudents() {
        $students = Student::latest()->limit(10)->get();
        $newStudents = [];
        foreach($students as $student) {
            $user = $student->user()->get()->get(0);
            $newStudents[] = array_merge($user->toArray(), $student->toArray());
        }

        return new StudentResource($newStudents);
    }

    public function studentPaid(Student $student, int $level) {
        $user = $student->user()->get()->get(0);
        $hasPaid = [];
        if($user->payments()->exists()) {
            $hasPaid = $user->payments()->where('level', $level)->get()->toArray();            
        }
        return new StudentResource($hasPaid);

    }

    public function studentPaymentsInfo(Student $student) {
        $user = $student->user()->get()->get(0);
        $paymentsInfo = [];
        if($user->payments()->exists()) {
            foreach($user->payments()->get() as $payment) {
                $paymentsInfo[] = $payment;
            }
        }
        return new StudentResource($paymentsInfo);
    }
    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $response = [];
        $user = $student->user()->get();
        $student = $student->where('user_id', $user->get(0)->id)->get();
        $response[] = array_merge(...$student->toArray(), ...[0 => ["student_id" => $student->get(0)->id]], ...$user->toArray(),);
        
        
        return new StudentResource($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validatedData = $request->validated();
       
        $department = Department::where("department_name", $validatedData['department_name'])->get();
        $stream = Stream::where("stream_name", $validatedData['stream_name'])->get();
        $section = Section::where("section_name", $validatedData['section_name'])->get();

        $student->update([
            "stream_id" => $stream->get(0)->id,
            "section_id" => $section->get(0)->id,
            "level" => $validatedData['level'],
            "current_level" => $validatedData['current_level'],
            "student_username" => $validatedData['student_username'],
        ]);

        $student->user()->update([
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
        $user = $student->user()->get();
        $student = $student->where('user_id', $user->get(0)->id)->get();
        $response[] = array_merge(...$student->toArray(), ...[0 => ["student_id" => $student->get(0)->id]], ...$user->toArray(),);

        return new StudentResource($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->user()->delete();
        $student->delete();

        return new StudentResource(["messages" => "Student Deleted Successfully"]);
    }
}
