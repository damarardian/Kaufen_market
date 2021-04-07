<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\VerifyApiEmail;
use App\Models\Data;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',        
        'image'
        
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_ _at' => 'datetime',
    ];

    public function data()
    {       
        return $this->hasMany(Data::class, 'user_id');
    }

    public function koperasi()
    {       
        return $this->hasMany(Koperasi::class, 'user_id');
    }

    public function barang()
    {       
        return $this->hasMany(Barang::class, 'user_id');
    }

    public function loan()
    {       
        return $this->hasMany(Loans::class, 'user_id');
    }

    public function deposit()
    {       
        return $this->hasMany(Deposit::class, 'user_id');
    }
 
    public function decstock(){
		return $this->hasMany(Decstock::class,'user_id');
	}

    public function pay(){
		return $this->hasMany(Pay::class,'user_id');
	}
}
