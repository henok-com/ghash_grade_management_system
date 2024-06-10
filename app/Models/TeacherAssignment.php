<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "stream_id",
        "section_id",
        "courses_id",
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

    public function course() {
        return $this->belongsTo(Courses::class);
    }
}
