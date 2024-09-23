<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post; 
use App\Models\Comment; 
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the posts for the user. Un utilisateur peut ecrit un ou plusieurs post relation (one to many)
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comment for the user. Un utilisateur peut ecrit un ou plusieurs comment, mais chaque enregistrement dans la table UserComment est associé à un seul utilisateur relation (many to one)
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
