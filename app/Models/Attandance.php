<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attandance extends Model
{
    use HasFactory;

    protected $fillable = ['check_in','check_out','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
