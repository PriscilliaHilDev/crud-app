<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post; 


class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    /**
     * Get the post associated with the image. recupérer le post lié à une image (relation one to one)
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
