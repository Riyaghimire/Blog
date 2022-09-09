<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Btag extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
      
    }

    public function blog()
    {
        return $this->hasMany(blog::class);
    }
}
