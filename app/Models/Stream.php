<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        "department_id",
        "stream_name",
        "maximum_students_in_stream",
        "maximum_students_in_section",
        "current_students_amount"
    ];

    public function students() {
        return $this->hasMany(Student::class);
    }
    
    public function sections() {
        return $this->hasMany(Section::class);
    }

    public function courses() {
        return $this->hasMany(Courses::class);
    }

    public function teacherAssignments() {
        return $this->hasMany(TeacherAssignment::class);
    }    
    
    public function department() {
        return $this->belongsTo(Department::class);
    }
}
