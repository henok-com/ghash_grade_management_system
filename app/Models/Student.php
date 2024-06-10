<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;


    protected $fillable = [
        "user_id",
        "department_id",
        "stream_id",
        "section_id",
        "level",
        "current_level",
        "student_username",
        "password"
    ];

    protected $hidden = [
        "user_id",
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function stream() {
        return $this->belongsTo(Stream::class);
    }

    public function section() {
        return $this->belongsTo(Section::class);
    }
}
