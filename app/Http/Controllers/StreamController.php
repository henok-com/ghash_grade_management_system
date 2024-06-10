<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\Department;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StoreStreamRequest;
use App\Http\Requests\UpdateStreamRequest;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return StreamResource::collection(Stream::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreamRequest $request)
    {
        $validatedData = $request->validated();
        $department = Department::where("department_name", $validatedData['department_name'])->get();
        $validatedData['department_id'] = $department->get(0)->id;
        $stream = Stream::create($validatedData);
        return new StreamResource($stream);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stream $stream)
    {
        return new StreamResource($stream);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreamRequest $request, Stream $stream)
    {
        $validatedData = $request->validated();
        $department = Department::where("department_name", $validatedData['department_name'])->get();
        $validatedData['department_id'] = $department->get(0)->id;
        $stream->update($validatedData);
        return new StreamResource($stream);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stream $stream)
    {
        $stream->delete();
        return new StreamResource(["messages" => "Stream Deleted Successfully"]);
    }
}
