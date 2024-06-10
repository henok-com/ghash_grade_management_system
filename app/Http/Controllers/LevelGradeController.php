<?php

namespace App\Http\Controllers;

use App\Models\LevelGrade;
use App\Http\Resources\LevelGradeResource;
use App\Http\Requests\StoreLevelGradeRequest;
use App\Http\Requests\UpdateLevelGradeRequest;

class LevelGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function highScoredStudents(int $level) {
        $highScores = LevelGrade::where("level", "=",  $level)->where( "GPA", '>=', 3)->orderBy('GPA', 'desc')->limit(10)->get();
        return LevelGradeResource::collection($highScores);
    }

    public function lowScoredStudents(int $level) {
        $lowScores = LevelGrade::where('level', '=', $level)->where('GPA', "<" , 2)->orderBy('GPA', 'asc')->limit(10)->get();
        return LevelGradeResource::collection($lowScores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelGradeRequest $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(LevelGrade $levelGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelGradeRequest $request, LevelGrade $levelGrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LevelGrade $levelGrade)
    {
        //
    }
}
