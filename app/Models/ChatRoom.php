<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatRoom extends Model
{
    use HasFactory;

    public function chatmembers() {
        return $this->hasMany(ChatMember::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
