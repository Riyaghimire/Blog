<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Blog extends Model
{
    use HasFactory;
    use Searchable;
    
    protected $fillable = [
        'title',
        'btag_id',
        'image',
        'description',
        
    ];
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
      
    }

    public function btag()
    {
        return $this->belongsTo(btag::class);
    }
    public function category(){
        return $this->belongsToMany(Blog::class);
    }
}