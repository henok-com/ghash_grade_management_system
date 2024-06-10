<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courses extends Model
{
    use HasFactory;

    public function stream() {
        return $this->belongsTo(Stream::class);
    }

    public function grades() {
        return $this->hasMany(Grade::class);
    }

    public function teacherAssignments() {
        return $this->hasMany(TeacherAssignment::class);
    }    

    public function coursePlans() {
        return $this->hasMany(CoursePlan::class);
    }
}
