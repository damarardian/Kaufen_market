<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decstock extends Model
{
    use HasFactory;

    public function barang()
    {
        return $this->belongsTo(Barang::class);        
    }

    

    public function user()
    {
        return $this->belongsTo(User::class);        
    }
}
