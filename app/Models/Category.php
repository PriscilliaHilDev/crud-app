<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post; 


class Category extends Model
{
    use HasFactory;

    /**
     * The posts that belong to the category. un ou plusieurs postes appartien(nent) a plusieurs categories (relation many yo many)
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }
}
