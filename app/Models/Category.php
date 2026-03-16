<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug'];

    // Thể loại này có nhiều phim
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'category_movie');
    }
}
