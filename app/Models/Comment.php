<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
use App\Models\Post; 


class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'content',
        'user_id',
        'post_id',
    ];

    /**
     * Get the User that owns the comment. Un utilisateur est l'auteur d'un seul commentaire (relation many to one)
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get the post associated with the comment. récuperer le post associé a un comment, chaque post a un ou plusieurs commentaires mais chaque enregistrement de la table comment peuvent etre associé au un meme post (relation one to many)
    */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
