<?php

namespace App\Models;

use App\Models\Post; 
use App\Models\User; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comment extends Model
{
    use HasFactory;
    use SoftDeletes; // Activation du soft delete pour ce modèle

    
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
