<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    public function data()
    {
        return $this->belongsTo(Data::class);        
    }

    public function user()
    {
        return $this->belongsTo(User::class);        
    }
}
