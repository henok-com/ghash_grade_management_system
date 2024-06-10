<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'gender',
        'address',
        'email',
        'phone_number',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function admin() {
        return $this->hasOne(Admin::class);
    }

    public function student() {
        return $this->hasOne(Student::class);
    }

    public function teacher() {
        return $this->hasOne(Teacher::class);
    }

    public function profilePictures() {
        return $this->hasMany(ProfilePicture::class);
    }

    public function chatMember() {
        return $this->hasMany(ChatMember::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function grades() {
        return $this->hasMany(Grade::class);
    }

    public function levelGrades() {
        return $this->hasMany(LevelGrade::class);
    }

    public function teacherAssignments() {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

}
