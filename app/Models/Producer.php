<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;
    protected $fillable = ['firstName', 'lastName', 'email', 'phone', 'address', 'zip', 'city', 'description', 'picture', 'category_id'];


public function label()
{
    //Relation many to many avec labels
    return $this->belongsToMany('App\Models\Label');
}
}