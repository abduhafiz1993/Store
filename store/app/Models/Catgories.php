<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catgories extends Model
{
    /** @use HasFactory<\Database\Factories\CatgoriesFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }   
}
