<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Department;
use App\Http\Middleware\isAdmin;
use App\Http\Resources\AdminResource;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminController extends Controller implements HasMiddleware
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
        foreach(Admin::all() as $admin) {
            $user = $admin->user()->get();
            $admin = $admin->where('user_id', $user->get(0)->id)->get();
            $response[] = array_merge(...$admin->toArray(), ...[0 => ["admin_id" => $admin->get(0)->id]], ...$user->toArray(),);
        }
        
        
        return new AdminResource($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        
    }

    public function promoteTeacher(Teacher $teacher) {
        $user = $teacher->user()->first();
        $admin_username = str_replace("TEA", "ADM", $teacher->teacher_username);
        Admin::create([
            "user_id" => $teacher->user_id,
            "admin_username" => $admin_username,
            "password" => $teacher->password,
        ]);
        $teacher->delete();
        return new AdminResource(["messsages" => "Teacher" . " " . $user->first_name . " " . "Successfully Promoted to Administrator"]);

    }

    public function depromoteTeacher(Admin $admin) {
        $user = $admin->user()->first();
        $teacher_username = str_replace("ADM", "TEA", $admin->teacher_username);
        Teacher::create([
            "user_id" => $admin->user_id,
            "teacher_username" => $teacher_username,
            "password" => $admin->password,
        ]);
        $admin->delete();
        return new AdminResource(["messsages" => "Administrator" . " " . $user->first_name . " " . "Successfully Depromoted to Teacher"]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        $response = [];

        $user = $admin->user()->get();
        $admin = $admin->where("user_id", $user->get(0)->id)->get();

        $response[] = array_merge(...$admin->toArray(), ...[0 => ["admin_id" => $admin->get(0)->id]], ...$user->toArray(),);

        return new AdminResource($response);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $validatedData = $request->validated();

        $department = Department::where("department_name", $validatedData['department_name'])->get();
        $admin->update(["admin_username" => $validatedData['admin_username']]);
        $admin->user()->update([
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

        $user = $admin->user()->get();
        $admin = $admin->where("user_id", $user->get(0)->id)->get();

        $response[] = array_merge(...$admin->toArray(), ...[0 => ["admin_id" => $admin->get(0)->id]], ...$user->toArray(),);

        return new AdminResource($response);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->user()->delete();
        $admin->delete();

        return new AdminResource(["messages" => "Admin Deleted Successfully"]);
    }
}
