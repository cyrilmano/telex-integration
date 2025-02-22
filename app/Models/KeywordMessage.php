<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordMessage extends Model
{
    use HasFactory;

    protected $fillable = ['keyword', 'message', 'sender', 'received_at'];
}
