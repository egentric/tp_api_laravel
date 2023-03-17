<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $fillable = ['categoryName'];

    public function category()
    {
        // Relation one to many
    return $this->hasMany(category::class);
    }
}
