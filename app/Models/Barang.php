<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'data_id',
        'name_barang',
        'stock',
        'jenis',
        'harga',
        'image',
        'dec_stock'
    ];
    
    public function data()
    {
        return $this->belongsTo(Data::class);        
    }

    public function user()
    {
        return $this->belongsTo(User::class);        
    }

    public function cart(){
		return $this->hasMany(Cart::class, 'barang_id');
	}

    public function img(){
		return $this->hasMany(Image::class, 'image_id');
	}

    public function decstock()
    {       
        return $this->hasMany(Decstock::class, 'barang_id');
    }
}
