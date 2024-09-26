<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
use App\Models\Category; 
use App\Models\Image; 
use App\Models\Comment; 
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes; // Activation du soft delete pour ce modèle


    protected $fillable = [
        'title',
        'content',   
        'user_id'
    ];

    /**
     * Get the user (author) that owns the post. Un post appartient à un seul utilisateur, récuperer cette utilisateur mais un utilisateur peut ecrire plusieurs post (relation many to one)
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The categories that belong to the post. un ou plusieurs post possed(ent) un ou plusieurs categories, recuperer les categories d'un post (relation many to many)
    */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    /**
     * Get the image associated with the post. un post a une seule image (relation one to one)
    */
    public function image()
    {
        return $this->hasOne(Image::class);
    }

    /**
     * Get the commments associated with the post.  recupérer le ou les commentaire du post, chaque post a un ou plusieurs commentaires (relation one to many)
    */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}

