<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;


    protected $fillable = [
      "user_id",
      "teacher_username",
      "password",
    ];

    protected $hidden = [
        "password",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
