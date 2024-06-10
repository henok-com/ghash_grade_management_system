<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    public function course() {
        return $this->belongsTo(Courses::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
