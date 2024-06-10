<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        "stream_id",
        "section_name",
        "current_students_amount",
    ];

    public function students() {
        return $this->hasMany(Student::class);
    }

    public function teacherAssignments() {
        return $this->hasMany(TeacherAssignment::class);
    }    

    public function stream() {
        return $this->belongsTo(Stream::class);
    }
}
