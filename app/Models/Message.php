<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['title', 'message', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }
}
