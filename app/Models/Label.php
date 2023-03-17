<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    protected $fillable = ['labelName', 'labelPicture',];

    public function producer()
{
        //Relation many to many avec producers
    return $this->belongsToMany('App\Models\Producer');
}

}
