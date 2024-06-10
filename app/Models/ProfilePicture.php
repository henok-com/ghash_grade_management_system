<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilePicture extends Model
{
    use HasFactory;

    protected $fillable = [
        "profile_picture_path",
        "user_id",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
