<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Http\Resources\TeacherAssignmentResource;
use App\Http\Requests\StoreTeacherAssignmentRequest;
use App\Http\Requests\UpdateTeacherAssignmentRequest;

class TeacherAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeacherAssignmentResource::collection(TeacherAssignment::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherAssignmentRequest $request)
    {
        $validatedData = $request->validated();

        $user = Teacher::find($validatedData['teacher_id'])->user()->first();
        $validatedData['user_id'] = $user->id;
        $teacherAssignment = TeacherAssignment::create($validatedData);
        return new TeacherAssignmentResource($teacherAssignment);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherAssignment $teacherAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherAssignmentRequest $request, TeacherAssignment $teacherAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherAssignment $teacherAssignment)
    {
        //
    }
}
