<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProfilePicture;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfilePictureResource;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreProfilePictureRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\UpdateProfilePictureRequest;

class ProfilePictureController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware() {
        return [
            new Middleware("auth:sanctum"),
        ];
    }

    public function index(Request $request)
    {
        $profilePictures = auth()->user()->profilePictures()->get();
        return ProfilePictureResource::collection($profilePictures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilePictureRequest $request)
    {
        $validatedData = $request->validated();
        $image = $validatedData['image'];
        
        $imagePath = "images/".Str::random()."/";

        Storage::disk("public")->put($imagePath, $image);
        $image_path = $imagePath.$image->hashName();

        $pp = ProfilePicture::create([
            "profile_picture_path" => "storage/".$image_path,
            "user_id" => auth()->user()->id,
        ]);

        return new ProfilePictureResource($pp);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfilePicture $profilePicture)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilePictureRequest $request, ProfilePicture $profilePicture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfilePicture $profilePicture)
    {
        if($profilePicture->user_id == auth()->user()->id) {
            $profilePicture->delete();
            return response()->json(["messages" => "Profile Picture Deleted Successfully"]);
        } else {
            return response()->json(["messages"=> "Unauthorized delete command"]);
        }
    }
}
