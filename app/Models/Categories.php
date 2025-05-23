<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        
    ];
    



    //++++++++++++++//
    public function User()
    {
        return $this->belongsTo(User::class);
    }




    //++++++++++++++//
    public function Products()
    {
        return $this->hasMany(Products::class);
    }
}
