<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isTeacher;
use App\Http\Resources\GradeResource;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class GradeController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware() {
        return [
            // new Middleware("auth:sanctum"),
            // new Middleware(isAdmin::class, $only = ["index"]),
            // new Middleware(isTeacher::class, $except = ["index"]),
        ];
    }


    public function reviewGrade(User $user) {
        if($user->student()->exists() && $user->grades()->exists()) {
            return GradeResource::collection($user->grades()->get());
        } else {
            return response()->json(new GradeResource(["messages" => "There is no grade for this students"]));
        }
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }
}
