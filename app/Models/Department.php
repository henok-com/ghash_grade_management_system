<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        "department_name",
    ];

    public function users() {
        return $this->hasMany(Student::class);
    }

    public function streams() {
        return $this->hasMany(Stream::class);
    }

}
