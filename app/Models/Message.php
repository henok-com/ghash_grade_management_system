<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    public function chatroom() {
        return $this->belongsTo(Chatroom::class);
    }

    public function chatmember() {
        return $this->belongsTo(ChatMember::class);
    }
}
