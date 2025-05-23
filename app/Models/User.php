<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Categories;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // أضف هذا السطر
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // أضف HasApiTokens هنا

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // ✅ دالة لمعرفة هل المستخدم أدمن
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ✅ دالة لمعرفة هل المستخدم مشرف (Moderator)
    public function isModerator()
    {
        return $this->role === 'moderator';
    }




    //+++++++++++++//
    public function category()
    {
        return $this->hasMany(Categories::class);
    }


    //++++++++++++++//
    public function Products()
    {
        return $this->hasMany(Products::class);
    }
    //++++++++++++++//
  public function cart()
{
    return $this->hasMany(Cart::class);
}

    
   
}



