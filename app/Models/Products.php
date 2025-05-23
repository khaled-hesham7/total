<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'image',
        'price',
        'quantity',
        'category_id',
        'user_id',
    ];








    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
    //****************//
    public function User()
    {
        return $this->belongsTo(User::class);
    }
    //****************//
   public function carts()
{
    return $this->hasMany(Cart::class);
}

}